<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ─── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $orders = Order::with([
            'user',
            'items',
            'payment',
            'shipment',
            'addresses',
        ])->latest()->get();

        $filteredOrders = $request->filled('status')
            ? $orders->where('status', $request->status)
            : $orders;

        return view('orders.index', compact('orders', 'filteredOrders'));
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $order = Order::with([
            'user',
            'items.product.images',
            'items.variant',
            'payment',
            'shipment',
            'addresses',
            'coupon',
            'reviews.product',
        ])->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    // ─── Update Status ────────────────────────────────────────────────────────

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'resi'   => 'nullable|string|max:100',
            'note'   => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $order->update(['status' => $request->status]);

            // Update / create shipment resi when status = shipped
            if ($request->status === 'shipped' && $request->filled('resi')) {
                $shipment = $order->shipment ?? new Shipment(['order_id' => $order->id]);
                $shipment->resi   = $request->resi;
                $shipment->status = 'in_transit';
                $shipment->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    // ─── Update Resi only ─────────────────────────────────────────────────────

    public function updateResi(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate(['resi' => 'required|string|max:100']);

        $shipment = $order->shipment ?? new Shipment(['order_id' => $order->id]);
        $shipment->resi   = $request->resi;
        $shipment->status = 'in_transit';
        $shipment->save();

        return redirect()->back()->with('success', 'Nomor resi berhasil disimpan.');
    }

    // ─── API: show (for fetch) ─────────────────────────────────────────────────

    public function showApi($id)
    {
        $order = Order::with([
            'user',
            'items.product.images',
            'items.variant',
            'payment',
            'shipment',
            'addresses',
            'coupon',
            'reviews',
        ])->findOrFail($id);

        return response()->json($order);
    }
}
