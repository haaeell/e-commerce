<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    const SUPPORTED_COURIERS = [
        'jne'      => 'JNE',
        'tiki'     => 'TIKI',
        'pos'      => 'POS Indonesia',
        'sicepat'  => 'SiCepat',
        'jnt'      => 'J&T Express',
        'anteraja' => 'Anteraja',
        'ninja'    => 'Ninja Xpress',
        'lion'     => 'Lion Parcel',
    ];

    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with(['items.product.images', 'items.variant.attributes'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $address      = $user->addresses()->where('is_default', true)->first();
        $carts        = $cart->items;
        $total_price  = 0;
        $total_weight = 0;

        foreach ($carts as $item) {
            $total_price  += (float) $item->price * (int) $item->qty;
            $total_weight += ($item->product->weight ?? 1000) * $item->qty;
        }

        $couriers = self::SUPPORTED_COURIERS;

        return view('user.checkout.index', compact(
            'carts',
            'total_price',
            'total_weight',
            'address',
            'couriers'
        ));
    }

    public function setAddress(Request $request)
    {
        $request->validate(['address_id' => 'required|exists:user_addresses,id']);

        $address = UserAddress::where('id', $request->address_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('checkout.index');
    }

    public function checkOngkir(Request $request)
    {
        $request->validate([
            'couriers' => 'required|array|min:1',
            'weight'   => 'required|integer|min:1',
        ]);

        $address = Auth::user()->addresses()->where('is_default', true)->first();

        if (!$address) {
            return response()->json(['error' => 'Alamat pengiriman belum dipilih.'], 400);
        }

        if (!$address->rajaongkir_destination_id) {
            return response()->json(['error' => 'Data alamat belum lengkap. Silakan edit dan pilih ulang lokasi tujuan.'], 400);
        }

        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY'),
        ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin'      => env('RAJAONGKIR_ORIGIN'),
            'destination' => $address->rajaongkir_destination_id,
            'weight'      => max(1, (int) $request->weight),
            'courier'     => implode(':', $request->couriers),
            'price'       => 'lowest',
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal menghubungi API RajaOngkir.', 'details' => $response->json()], 500);
        }

        $data = $response->json();

        if (!isset($data['data']) || !is_array($data['data'])) {
            return response()->json(['error' => 'Format response API tidak valid.', 'raw' => $data], 500);
        }

        if (empty($data['data'])) {
            return response()->json(['error' => 'Tidak ada layanan pengiriman tersedia untuk rute ini.'], 404);
        }

        return response()->json($data['data']);
    }

    public function searchDestination(Request $request)
    {
        $request->validate(['search' => 'required|string|min:3']);

        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY'),
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
            'search' => $request->search,
            'limit'  => 10,
            'offset' => 0,
        ]);

        if ($response->failed()) {
            return response()->json([], 500);
        }

        return response()->json($response->json()['data'] ?? []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id'      => 'required|exists:addresses,id',
            'courier_code'    => 'required|string',
            'courier_service' => 'required|string',
            'shipping_cost'   => 'required|integer|min:0',
            'shipping_etd'    => 'nullable|string',
        ]);

        $user = Auth::user();
        $cart = Cart::with(['items.product', 'items.variant'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $subtotal    = $cart->items->sum(fn($i) => $i->price * $i->qty);
        $grandTotal  = $subtotal + $request->shipping_cost;

        DB::transaction(function () use ($request, $user, $cart, $subtotal, $grandTotal) {
            $order = Order::create([
                'user_id'          => $user->id,
                'address_id'       => $request->address_id,
                'courier_code'     => $request->courier_code,
                'courier_service'  => $request->courier_service,
                'shipping_cost'    => $request->shipping_cost,
                'shipping_etd'     => $request->shipping_etd,
                'subtotal'         => $subtotal,
                'grand_total'      => $grandTotal,
                'status'           => 'pending',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'qty'        => $item->qty,
                    'price'      => $item->price,
                ]);
            }

            $cart->items()->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}
