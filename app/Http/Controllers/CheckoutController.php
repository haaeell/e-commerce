<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with(['items.product.images', 'items.variant.attributes'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $address = $user->addresses()->where('is_default', true)->first();
        $carts = $cart->items;

        $total_price = 0;
        $total_weight = 0;

        foreach ($carts as $item) {
            $total_price += (float)$item->price * (int)$item->qty;
            $total_weight += ($item->product->weight ?? 1000) * $item->qty;
        }

        return view('user.checkout.index', compact('carts', 'total_price', 'total_weight', 'address'));
    }

    public function checkOngkir(Request $request)
    {
        $address = auth()->user()->addresses()->where('is_default', true)->first();

        if (!$address) {
            return response()->json(['error' => 'Alamat belum disetting'], 400);
        }

        $cityId = $this->getCityIdByName($address->city);

        if (!$cityId) {
            return response()->json(['error' => 'Kota tidak ditemukan di RajaOngkir'], 404);
        }

        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->post('https://api.rajaongkir.com/starter/cost', [
            'origin'        => env('RAJAONGKIR_ORIGIN'),
            'destination'   => $cityId,
            'weight'        => $request->weight ?? 1000,
            'courier'       => $request->courier
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'API RajaOngkir Error',
                'details' => $response->json()
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['rajaongkir']['results'][0]['costs'])) {
            return response()->json([
                'error' => 'Format data RajaOngkir tidak valid',
                'raw' => $data
            ], 500);
        }

        return $data['rajaongkir']['results'][0]['costs'];
    }

    private function getCityIdByName($cityName)
    {
        $response = Http::withHeaders(['key' => env('RAJAONGKIR_API_KEY')])
            ->get('https://api.rajaongkir.com/starter/city');

        // Cek apakah request ke RajaOngkir berhasil
        if ($response->successful()) {
            $result = $response->json();

            if (isset($result['rajaongkir']['results'])) {
                $cities = collect($result['rajaongkir']['results']);

                $find = $cities->filter(function ($item) use ($cityName) {
                    return strtolower($item['city_name']) == strtolower($cityName) ||
                        strtolower($item['type'] . ' ' . $item['city_name']) == strtolower($cityName);
                })->first();

                return $find['city_id'] ?? null;
            }
        }

        return null;
    }
}
