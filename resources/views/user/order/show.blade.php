@extends('layouts.customer')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
    <section class="py-12 bg-[#F8F9FA] min-h-screen px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            @php
                $statusMap = [
                    'pending' => [
                        'bg' => 'bg-amber-50',
                        'text' => 'text-amber-600',
                        'label' => 'Pending',
                        'icon' => 'fa-clock',
                    ],
                    'confirmed' => [
                        'bg' => 'bg-blue-50',
                        'text' => 'text-blue-600',
                        'label' => 'Dikonfirmasi',
                        'icon' => 'fa-check-circle',
                    ],
                    'processing' => [
                        'bg' => 'bg-indigo-50',
                        'text' => 'text-indigo-600',
                        'label' => 'Diproses',
                        'icon' => 'fa-box',
                    ],
                    'shipped' => [
                        'bg' => 'bg-cyan-50',
                        'text' => 'text-cyan-600',
                        'label' => 'Dikirim',
                        'icon' => 'fa-truck',
                    ],
                    'delivered' => [
                        'bg' => 'bg-green-50',
                        'text' => 'text-green-600',
                        'label' => 'Terkirim',
                        'icon' => 'fa-check-double',
                    ],
                    'cancelled' => [
                        'bg' => 'bg-red-50',
                        'text' => 'text-red-600',
                        'label' => 'Dibatalkan',
                        'icon' => 'fa-xmark',
                    ],
                    'refunded' => [
                        'bg' => 'bg-purple-50',
                        'text' => 'text-purple-600',
                        'label' => 'Refund',
                        'icon' => 'fa-rotate-left',
                    ],
                ];
                $currentStatus = $statusMap[$order->status] ?? $statusMap['pending'];
            @endphp

            

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div class="flex items-center gap-4">
                    <a href="{{ route('order.history') }}"
                        class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-brand-dark shadow-sm border border-gray-100 hover:bg-brand-primary hover:text-white transition-all">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-brand-dark tracking-tight">Detail Pesanan</h1>
                        <p class="text-sm text-gray-500 italic">No. Referensi: <span
                                class="font-bold">#{{ $order->order_number }}</span></p>
                    </div>
                </div>

                <!-- Status Badge Dinamis -->
                <div
                    class="flex items-center gap-3 {{ $currentStatus['bg'] }} p-2 pr-6 rounded-2xl border border-{{ str_replace('text-', '', $currentStatus['text']) }}/20 w-fit">
                    <div
                        class="w-10 h-10 {{ str_replace('text-', 'bg-', $currentStatus['text']) }} rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid {{ $currentStatus['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest leading-none">Status Pesanan
                        </p>
                        <p class="text-sm font-black {{ $currentStatus['text'] }} uppercase">{{ $currentStatus['label'] }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">

                    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
                        @if (in_array($order->status, ['cancelled', 'refunded']))
                            <!-- Tampilan Jika Batal/Refund -->
                            <div
                                class="flex items-center gap-4 p-4 rounded-2xl {{ $currentStatus['bg'] }} border border-dashed border-{{ str_replace('text-', '', $currentStatus['text']) }}/50">
                                <i
                                    class="fa-solid {{ $currentStatus['icon'] }} {{ $currentStatus['text'] }} text-2xl"></i>
                                <div>
                                    <h4 class="font-bold {{ $currentStatus['text'] }}">Pesanan ini telah
                                        {{ $currentStatus['label'] }}</h4>
                                    <p class="text-xs {{ $currentStatus['text'] }} opacity-80">Waktu update:
                                        {{ $order->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @else
                            <!-- Stepper Jalur Normal -->
                            <div class="relative flex justify-between">
                                @php
                                    $steps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                                    $currentIndex = array_search($order->status, $steps);
                                @endphp

                                <div class="absolute top-5 left-0 w-full h-1 bg-gray-100 -z-0">
                                    <div class="h-full bg-brand-primary transition-all duration-700"
                                        style="width: {{ $currentIndex !== false ? ($currentIndex / (count($steps) - 1)) * 100 : 0 }}%">
                                    </div>
                                </div>

                                @foreach ($steps as $index => $step)
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white transition-all 
                                            {{ $currentIndex >= $index ? 'bg-brand-primary text-white' : 'bg-gray-200 text-gray-400' }}">
                                            <i class="fa-solid {{ $statusMap[$step]['icon'] }} text-[10px]"></i>
                                        </div>
                                        <span
                                            class="mt-2 text-[9px] font-bold uppercase tracking-tighter {{ $currentIndex >= $index ? 'text-brand-dark' : 'text-gray-300' }}">
                                            {{ $statusMap[$step]['label'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Product List -->
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                            <h3 class="font-bold text-brand-dark flex items-center gap-2">
                                <i class="fa-solid fa-bag-shopping text-brand-primary"></i>
                                Daftar Belanja
                            </h3>
                        </div>
                        <div class="p-6 divide-y divide-gray-100">
                            @foreach ($order->items as $item)
                                <div class="py-6 first:pt-0 last:pb-0 flex gap-6">
                                    <div
                                        class="w-24 h-24 rounded-2xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-100 shadow-sm">
                                        @php $primaryImage = $item->product->images->where('is_primary', true)->first(); @endphp
                                        <img src="{{ $primaryImage ? asset('storage/' . $primaryImage->image_url) : 'https://via.placeholder.com/400x533' }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow flex flex-col justify-center">
                                        <span
                                            class="text-[10px] font-bold text-brand-primary uppercase tracking-[0.2em] mb-1">{{ $item->product->category->name }}</span>
                                        <h4 class="font-bold text-brand-dark text-lg leading-tight mb-2">
                                            {{ $item->product->name }}</h4>

                                        @if ($item->variant)
                                            <div class="flex gap-2 mb-3">
                                                @foreach ($item->variant->attributes as $attr)
                                                    <span
                                                        class="px-2.5 py-1 bg-white text-gray-600 rounded-lg text-[10px] font-bold border border-gray-200 shadow-sm">
                                                        {{ $attr->attribute_value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-center">
                                            <p class="text-sm text-gray-400"><span
                                                    class="font-bold text-brand-dark">{{ $item->qty }}x</span>
                                                Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                            <p class="font-black text-brand-dark">
                                                Rp{{ number_format($item->qty * $item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Shipment Tracking (If Shipped) -->
                    @if ($order->shipment && $order->shipment->resi)
                        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="font-bold text-brand-dark flex items-center gap-3 text-lg">
                                    <div class="w-10 h-10 bg-brand-primary/10 rounded-xl flex items-center justify-center">
                                        <i class="fa-solid fa-map-location-dot text-brand-primary"></i>
                                    </div>
                                    Lacak Pesanan
                                </h3>
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase leading-none mb-1">No. Resi
                                        ({{ $order->shipment->courier }})</p>
                                    <span
                                        class="font-mono font-bold text-brand-dark bg-gray-100 px-3 py-1 rounded-lg text-sm tracking-tighter italic">
                                        {{ $order->shipment->resi }}
                                    </span>
                                </div>
                            </div>

                            @if ($tracking && isset($tracking['manifest']))
                                <div
                                    class="relative pl-8 space-y-8 before:content-[''] before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-100">
                                    @foreach (array_reverse($tracking['manifest']) as $log)
                                        <div class="relative">
                                            <div
                                                class="absolute -left-[27px] top-1 w-4 h-4 rounded-full border-4 border-white {{ $loop->first ? 'bg-brand-primary ring-4 ring-brand-primary/20' : 'bg-gray-300' }}">
                                            </div>
                                            <div class="flex flex-col md:flex-row md:justify-between gap-2">
                                                <div>
                                                    <p
                                                        class="text-sm font-bold {{ $loop->first ? 'text-brand-dark' : 'text-gray-500' }}">
                                                        {{ $log['manifest_description'] }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 font-medium">{{ $log['city_name'] }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="flex flex-row md:flex-col items-center md:items-end gap-2 md:gap-0">
                                                    <p class="text-[10px] font-bold text-brand-primary uppercase">
                                                        {{ \Carbon\Carbon::parse($log['manifest_date'])->format('d M Y') }}
                                                    </p>
                                                    <p class="text-[10px] text-gray-400 font-medium">
                                                        {{ $log['manifest_time'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-amber-50 border border-amber-100 p-5 rounded-2xl flex items-start gap-4">
                                    <i class="fa-solid fa-circle-info text-amber-500 mt-1"></i>
                                    <div>
                                        <p class="text-sm text-amber-900 font-bold mb-1">Informasi Pelacakan Segera Hadir
                                        </p>
                                        <p class="text-xs text-amber-700/80 leading-relaxed">Kurir telah menerima permintaan
                                            pengiriman. Riwayat perjalanan paket Anda akan muncul secara otomatis di sini
                                            dalam
                                            beberapa jam.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Payment Summary Card -->
                    <div
                        class="bg-brand-dark text-white rounded-[40px] p-8 shadow-2xl shadow-brand-dark/30 relative overflow-hidden">
                        <!-- Ornamen -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-primary/10 rounded-full blur-3xl"></div>

                        <h3 class="font-bold text-lg mb-8 relative z-10">Ringkasan Biaya</h3>

                        <div class="space-y-4 mb-8 relative z-10">
                            <div class="flex justify-between text-sm">
                                <span>Total Harga ({{ $order->items->sum('qty') }} item)</span>
                                <span
                                    class="font-bold text-white italic">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Ongkos Kirim</span>
                                <span
                                    class="font-bold text-white italic">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            @if ($order->discount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class=" font-bold">Potongan Diskon</span>
                                    <span
                                        class=" font-bold">-Rp{{ number_format($order->discount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="pt-6 border-t border-white/10 flex justify-between items-end mb-8 relative z-10">
                            <span class="text-xs font-bold uppercase tracking-[0.2em]">Total Pembayaran</span>
                            <span
                                class="text-3xl font-black tracking-tighter">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>

                        <!-- Payment Method Info (NEW) -->
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/10 relative z-10">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[10px] font-bold  uppercase tracking-widest">Metode
                                    Bayar</span>
                                <span
                                    class="px-2 py-0.5 text-[9px] font-black rounded uppercase italic">
                                    {{ $order->payment->where('status', 'success')->first()->payment_method ?? 'MIDTRANS' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold  uppercase tracking-widest">Status</span>
                                <span class="text-xs font-black uppercase tracking-widest">PAID
                                    SUCCESS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address Card -->
                    <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100">
                        <h4 class="font-bold text-brand-dark mb-4 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-truck text-brand-primary text-xs"></i>
                            Informasi Pengiriman
                        </h4>
                        <div class="space-y-4">
                            <div class="pb-4 border-b border-gray-50">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Penerima</p>
                                <p class="text-sm font-bold text-brand-dark leading-tight">
                                    {{ $order->address->receiver_name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $order->address->phone }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Alamat Tujuan
                                </p>
                                <p class="text-xs text-gray-600 leading-relaxed italic">
                                    {{ $order->address->address }}, {{ $order->address->subdistrict }},
                                    {{ $order->address->district }}, {{ $order->address->city }},
                                    {{ $order->address->province }} ({{ $order->address->postal_code }})
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    @if ($order->status == 'shipped')
                        <form action="{{ route('order.history.complete', $order->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="w-full py-5 bg-brand-primary text-brand-dark font-black rounded-[24px] shadow-xl shadow-brand-primary/20 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 active:scale-95 flex items-center justify-center gap-3">
                                <i class="fa-solid fa-circle-check text-lg"></i>
                                Konfirmasi Pesanan Selesai
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
