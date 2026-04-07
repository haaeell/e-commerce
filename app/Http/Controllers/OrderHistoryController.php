<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $orders = Order::with(['items.product.images', 'items.variant'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $filteredOrders = $request->filled('status')
            ? $orders->where('status', $request->status)
            : $orders;

        return view('user.order.index', compact('orders', 'filteredOrders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'items.product.category',
            'items.product.images',
            'items.variant.attributes',
            'address',
            'shipment',
            'payment'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $tracking = null;
        if ($order->shipment && $order->shipment->resi) {
            $tracking = $this->getTrackingInfo(
                $order->shipment->resi,
                $order->shipment->courier
            );
        }

        return view('user.order.show', compact('order', 'tracking'));
    }

    private function getTrackingInfo($waybill, $courier)
    {
        if (str_contains($waybill, 'DUMMY')) {
            return [
                'manifest' => [
                    [
                        'manifest_description' => 'Pesanan sedang diproses di gudang pusat Al-Hayya',
                        'city_name' => 'Bandung',
                        'manifest_date' => now()->subDays(2)->format('Y-m-d'),
                        'manifest_time' => '09:00'
                    ],
                    [
                        'manifest_description' => 'Paket telah diserahkan ke kurir ' . strtoupper($courier),
                        'city_name' => 'Bandung',
                        'manifest_date' => now()->subDays(1)->format('Y-m-d'),
                        'manifest_time' => '14:30'
                    ],
                    [
                        'manifest_description' => 'Paket sedang transit di Hub Jakarta Selatan',
                        'city_name' => 'Jakarta',
                        'manifest_date' => now()->format('Y-m-d'),
                        'manifest_time' => '08:15'
                    ],
                    [
                        'manifest_description' => 'Paket dibawa kurir [Sdr. Budi] menuju lokasi penerima',
                        'city_name' => 'Jakarta',
                        'manifest_date' => now()->format('Y-m-d'),
                        'manifest_time' => '10:00'
                    ],
                ]
            ];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'key' => env('RAJAONGKIR_API_KEY'),
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/waybill/domestic-waybill', [
                'waybill' => $waybill,
                'courier' => $courier,
            ]);

            return $response->successful() ? $response->json()['data'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }
    public function markAsCompleted($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'shipped')
            ->findOrFail($id);

        $order->update([
            'status' => 'completed'
        ]);

        return redirect()->back()->with('success', 'Pesanan selesai');
    }
}
