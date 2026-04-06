@extends('layouts.app')

@section('title', 'Pesanan')

@section('content')
    <div class="mx-auto">

        {{-- ── Page Header ── --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-xl md:text-2xl font-extrabold text-brand-dark tracking-tight">Pesanan</h1>
                <nav class="text-xs md:text-sm text-gray-400 font-medium mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="/home" class="hover:text-brand-primary transition-colors">Dashboard</a></li>
                        <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                        <li class="text-brand-dark">Pesanan</li>
                    </ol>
                </nav>
            </div>
            {{-- Export button --}}
            <button onclick="exportOrders()"
                class="px-5 py-3 bg-white border border-gray-200 text-gray-600 rounded-2xl font-bold shadow-sm hover:bg-gray-50 transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-export text-sm text-brand-primary"></i>
                <span class="hidden sm:inline text-sm">Export</span>
            </button>
        </div>

        {{-- ── Summary Cards ── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @php
                $totalOrders = $orders->count();
                $pending = $orders->whereIn('status', ['pending', 'confirmed'])->count();
                $processing = $orders->whereIn('status', ['processing', 'shipped'])->count();
                $done = $orders->where('status', 'delivered')->count();
                $totalRevenue = $orders->where('status', 'delivered')->sum('total');
            @endphp

            <div class="bg-white rounded-3xl px-5 py-4 border border-gray-50 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-2xl bg-brand-primary/10 flex items-center justify-center text-brand-primary">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 tracking-widest">TOTAL PESANAN</p>
                    <p class="text-2xl font-extrabold text-brand-dark leading-tight">{{ $totalOrders }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl px-5 py-4 border border-gray-50 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500">
                    <i class="fa-solid fa-clock text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 tracking-widest">MENUNGGU</p>
                    <p class="text-2xl font-extrabold text-brand-dark leading-tight">{{ $pending }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl px-5 py-4 border border-gray-50 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                    <i class="fa-solid fa-truck text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 tracking-widest">DIPROSES</p>
                    <p class="text-2xl font-extrabold text-brand-dark leading-tight">{{ $processing }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl px-5 py-4 border border-gray-50 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-2xl bg-green-50 flex items-center justify-center text-green-500">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 tracking-widest">SELESAI</p>
                    <p class="text-2xl font-extrabold text-brand-dark leading-tight">{{ $done }}</p>
                </div>
            </div>
        </div>

        {{-- ── Revenue Banner ── --}}
        <div
            class="bg-gradient-to-r from-brand-primary to-brand-dark rounded-3xl px-6 py-5 mb-6 flex items-center justify-between shadow-lg shadow-brand-primary/20">
            <div>
                <p class="text-[10px] font-black text-white/60 tracking-widest">TOTAL PENDAPATAN (TERKIRIM)</p>
                <p class="text-3xl font-extrabold text-white mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-white">
                <i class="fa-solid fa-wallet text-2xl"></i>
            </div>
        </div>

        {{-- ── Filter Tabs ── --}}
        <div class="bg-white rounded-3xl border border-gray-50 shadow-sm mb-4 px-4 py-2 flex gap-1 flex-wrap">
            @php
                $statusMap = [
                    '' => ['label' => 'Semua', 'icon' => 'fa-list', 'color' => 'text-gray-500'],
                    'pending' => ['label' => 'Pending', 'icon' => 'fa-hourglass-half', 'color' => 'text-amber-500'],
                    'confirmed' => ['label' => 'Dikonfirmasi', 'icon' => 'fa-circle-check', 'color' => 'text-blue-500'],
                    'processing' => ['label' => 'Diproses', 'icon' => 'fa-gear', 'color' => 'text-indigo-500'],
                    'shipped' => ['label' => 'Dikirim', 'icon' => 'fa-truck', 'color' => 'text-cyan-500'],
                    'delivered' => ['label' => 'Terkirim', 'icon' => 'fa-house-circle-check', 'color' => 'text-green-500'],
                    'cancelled' => ['label' => 'Dibatalkan', 'icon' => 'fa-circle-xmark', 'color' => 'text-red-500'],
                    'refunded' => ['label' => 'Refund', 'icon' => 'fa-rotate-left', 'color' => 'text-purple-500'],
                ];
                $activeFilter = request('status', '');
            @endphp
            @foreach($statusMap as $key => $cfg)
                <a href="{{ route('orders.index', $key ? ['status' => $key] : []) }}"
                    class="px-4 py-2.5 rounded-2xl text-[11px] font-black tracking-widest transition-all flex items-center gap-1.5 whitespace-nowrap
                        {{ $activeFilter === $key ? 'bg-brand-primary text-white shadow-sm' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-700' }}">
                    <i class="fa-solid {{ $cfg['icon'] }} text-[10px]"></i>
                    {{ $cfg['label'] }}
                    @php $cnt = $key ? $orders->where('status', $key)->count() : $orders->count(); @endphp
                    @if($cnt > 0)
                        <span
                            class="px-1.5 py-0.5 rounded-md text-[9px] font-black {{ $activeFilter === $key ? 'bg-white/20' : 'bg-gray-100' }}">{{ $cnt }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- ── Table ── --}}
        <div class="bg-white rounded-[32px] shadow-sm border border-gray-50 overflow-hidden px-6 py-8">
            <table id="datatable" class="w-full text-sm">
                <thead>
                    <tr class="text-gray-400 text-[11px] tracking-widest border-b border-gray-50">
                        <th class="px-4 py-4 text-left">No. Order</th>
                        <th class="px-4 py-4 text-left">Pelanggan</th>
                        <th class="px-4 py-4 text-left">Item</th>
                        <th class="px-4 py-4 text-left">Total</th>
                        <th class="px-4 py-4 text-left">Pembayaran</th>
                        <th class="px-4 py-4 text-left">Status</th>
                        <th class="px-4 py-4 text-left">Tanggal</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($filteredOrders as $i => $order)
                        <tr class="hover:bg-soft-bg/50 transition-colors">

                            {{-- Order Number --}}
                            <td class="px-4 py-5">
                                <div class="font-black text-brand-primary text-sm font-mono">{{ $order->order_number }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">ID #{{ $order->id }}</div>
                            </td>

                            {{-- Customer --}}
                            <td class="px-4 py-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-brand-primary/10 flex items-center justify-center text-brand-primary font-black text-xs flex-shrink-0">
                                        {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-brand-dark text-sm">{{ $order->user->name ?? '-' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $order->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Items count --}}
                            <td class="px-4 py-5">
                                <div class="font-bold text-gray-700">{{ $order->items->count() }} item</div>
                                <div class="text-[10px] text-gray-400 mt-0.5 max-w-[140px] truncate">
                                    {{ $order->items->pluck('product_name')->implode(', ') }}
                                </div>
                            </td>

                            {{-- Total --}}
                            <td class="px-4 py-5">
                                <div class="font-extrabold text-brand-dark text-sm">Rp
                                    {{ number_format($order->total, 0, ',', '.') }}</div>
                                @if($order->discount > 0)
                                    <div class="text-[10px] text-green-500 font-semibold mt-0.5">
                                        <i class="fa-solid fa-tag text-[8px]"></i> Diskon Rp
                                        {{ number_format($order->discount, 0, ',', '.') }}
                                    </div>
                                @endif
                                @if($order->shipping_cost > 0)
                                    <div class="text-[10px] text-gray-400 mt-0.5">
                                        Ongkir: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Payment --}}
                            <td class="px-4 py-5">
                                @php $payment = $order->payment; @endphp
                                @if($payment)
                                                <span
                                                    class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider
                                                            {{ $payment->status === 'success' ? 'bg-green-50 text-green-600' :
                                    ($payment->status === 'pending' ? 'bg-amber-50 text-amber-600' :
                                        ($payment->status === 'failed' ? 'bg-red-50 text-red-600' :
                                            ($payment->status === 'expired' ? 'bg-gray-100 text-gray-500' : 'bg-purple-50 text-purple-600'))) }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                                @if($payment->payment_method)
                                                    <div class="text-[10px] text-gray-400 mt-1">{{ strtoupper($payment->payment_method) }}</div>
                                                @endif
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider bg-gray-50 text-gray-400">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-5">
                                @php
                                    $statusCfg = [
                                        'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'label' => 'Pending'],
                                        'confirmed' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'label' => 'Dikonfirmasi'],
                                        'processing' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'label' => 'Diproses'],
                                        'shipped' => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-600', 'label' => 'Dikirim'],
                                        'delivered' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'label' => 'Terkirim'],
                                        'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'label' => 'Dibatalkan'],
                                        'refunded' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'label' => 'Refund'],
                                    ];
                                    $sc = $statusCfg[$order->status] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-500', 'label' => $order->status];
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider {{ $sc['bg'] }} {{ $sc['text'] }}">
                                    {{ $sc['label'] }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="px-4 py-5">
                                <div class="font-semibold text-gray-700 text-sm">{{ $order->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="w-9 h-9 flex items-center justify-center bg-brand-primary/10 text-brand-primary rounded-xl hover:bg-brand-primary hover:text-white transition-all shadow-sm"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}')"
                                        class="w-9 h-9 flex items-center justify-center bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm"
                                        title="Ubah Status">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    @if(in_array($order->status, ['pending', 'confirmed']))
                                        <button onclick="cancelOrder({{ $order->id }})"
                                            class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm"
                                            title="Batalkan">
                                            <i class="fa-solid fa-ban text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════
    STATUS MODAL
    ══════════════════════════════════════ --}}
    <div id="statusModal"
        class="fixed inset-0 hidden bg-slate-900/50 backdrop-blur-sm items-center justify-center z-[100] p-4">
        <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden border border-white/20">

            <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-brand-primary/10 flex items-center justify-center text-brand-primary">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-brand-dark">Ubah Status Pesanan</h3>
                        <p class="text-[10px] text-gray-400 font-bold tracking-widest">ORDER MANAGEMENT</p>
                    </div>
                </div>
                <button onclick="closeStatusModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="statusForm" method="POST" class="p-7 space-y-4">
                @csrf
                @method('PATCH')
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 tracking-widest">STATUS PESANAN</label>
                    <select name="status" id="statusSelect"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none text-sm font-semibold appearance-none">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Dikonfirmasi</option>
                        <option value="processing">Diproses</option>
                        <option value="shipped">Dikirim</option>
                        <option value="delivered">Terkirim</option>
                        <option value="cancelled">Dibatalkan</option>
                        <option value="refunded">Refund</option>
                    </select>
                </div>

                {{-- Resi field (show when shipped) --}}
                <div id="resiField" class="hidden space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 tracking-widest">NOMOR RESI</label>
                    <input type="text" name="resi" placeholder="Masukkan nomor resi pengiriman..."
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none text-sm font-semibold">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 tracking-widest">CATATAN <span
                            class="text-gray-300">(Opsional)</span></label>
                    <textarea name="note" rows="2" placeholder="Catatan perubahan status..."
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none text-sm font-semibold resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeStatusModal()"
                        class="flex-1 py-3 rounded-2xl text-xs font-black tracking-widest text-gray-400 hover:bg-gray-100 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 rounded-2xl bg-brand-primary text-white text-xs font-black tracking-widest shadow-lg shadow-brand-primary/20 hover:bg-brand-dark transition-all">
                        <i class="fa-solid fa-check mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#datatable').DataTable({
                    responsive: true,
                    order: [[6, 'desc']],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Cari pesanan...",
                        lengthMenu: "Show _MENU_",
                    }
                });
            });

            // Status Modal
            window.openStatusModal = function (orderId, currentStatus) {
                $('#statusForm').attr('action', `/orders/${orderId}/status`);
                $('#statusSelect').val(currentStatus);
                toggleResiField(currentStatus);
                $('#statusModal').removeClass('hidden').addClass('flex');
            }

            window.closeStatusModal = function () {
                $('#statusModal').addClass('hidden').removeClass('flex');
            }

            $('#statusSelect').on('change', function () {
                toggleResiField(this.value);
            });

            function toggleResiField(status) {
                if (status === 'shipped') {
                    $('#resiField').removeClass('hidden');
                } else {
                    $('#resiField').addClass('hidden');
                }
            }

            $('#statusModal').on('click', function (e) {
                if (e.target === this) closeStatusModal();
            });

            // Cancel Order
            window.cancelOrder = function (id) {
                Swal.fire({
                    title: 'Batalkan Pesanan?',
                    text: 'Pesanan akan dibatalkan dan tidak bisa dikembalikan ke status sebelumnya.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2D5A27',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Kembali',
                }).then(r => {
                    if (r.isConfirmed) {
                        $('<form>', { method: 'POST', action: `/orders/${id}/status` })
                            .append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }))
                            .append($('<input>', { type: 'hidden', name: '_method', value: 'PATCH' }))
                            .append($('<input>', { type: 'hidden', name: 'status', value: 'cancelled' }))
                            .appendTo('body').submit();
                    }
                });
            }

            window.exportOrders = function () {
                Swal.fire({
                    icon: 'info',
                    title: 'Export Pesanan',
                    text: 'Fitur export akan segera tersedia.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            }
        </script>
    @endpush
@endsection