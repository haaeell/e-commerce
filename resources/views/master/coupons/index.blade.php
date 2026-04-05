@extends('layouts.app')

@section('title', 'Coupons')

@section('content')
    <div class="mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-xl md:text-2xl font-extrabold text-brand-dark tracking-tight">Promo Coupons</h1>
                <nav class="text-xs md:text-sm text-gray-400 font-medium mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="/home" class="hover:text-brand-primary transition-colors">Dashboard</a></li>
                        <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                        <li class="text-brand-dark">Coupons</li>
                    </ol>
                </nav>
            </div>

            <button onclick="openCreateModal()"
                class="px-5 py-3 bg-brand-primary text-white rounded-2xl font-bold shadow-lg shadow-brand-primary/20 hover:bg-brand-dark transition-all flex items-center gap-2">
                <i class="fa-solid fa-ticket text-sm"></i>
                <span class="hidden sm:inline">Buat Kupon Baru</span>
            </button>
        </div>

        <div class="bg-white rounded-[32px] shadow-sm border border-gray-50 overflow-hidden px-6 py-8">
            <table id="datatable" class="w-full text-sm">
                <thead>
                    <tr class="text-gray-400 text-[11px] uppercase tracking-widest border-b border-gray-50">
                        <th class="px-4 py-4 text-left">Kode & Nama</th>
                        <th class="px-4 py-4 text-left">Potongan</th>
                        <th class="px-4 py-4 text-left">Kuota (Terpakai)</th>
                        <th class="px-4 py-4 text-left">Periode</th>
                        <th class="px-4 py-4 text-left">Status</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($coupons as $coupon)
                        <tr class="hover:bg-soft-bg/50 transition-colors">
                            <td class="px-4 py-5">
                                <div class="font-black text-brand-primary tracking-wider text-sm">{{ $coupon->code }}</div>
                                <div class="text-[11px] text-gray-400 font-bold uppercase">{{ $coupon->name }}</div>
                            </td>
                            <td class="px-4 py-5 font-bold text-brand-dark">
                                {{ $coupon->type == 'percentage' ? $coupon->value . '%' : 'Rp ' . number_format($coupon->value, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="text-brand-dark font-bold">{{ $coupon->used_count }}</span>
                                    <span class="text-gray-300">/</span>
                                    <span class="text-gray-400 font-medium">{{ $coupon->quota }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-[11px] font-semibold text-gray-500 uppercase">
                                {{ $coupon->started_at ? date('d M Y', strtotime($coupon->started_at)) : 'N/A' }} - 
                                {{ $coupon->expired_at ? date('d M Y', strtotime($coupon->expired_at)) : 'N/A' }}
                            </td>
                            <td class="px-4 py-5">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $coupon->is_active ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                    {{ $coupon->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openEditModal(@json($coupon))'
                                        class="w-9 h-9 flex items-center justify-center bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <button onclick="deleteCoupon({{ $coupon->id }})"
                                        class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="couponModal" class="fixed inset-0 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden transform transition-all border border-white/20">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-brand-primary shadow-lg shadow-brand-primary/20 flex items-center justify-center text-white">
                        <i class="fa-solid fa-ticket text-lg"></i>
                    </div>
                    <div>
                        <h2 id="modalTitle" class="text-lg font-extrabold text-brand-dark leading-tight">Buat Kupon</h2>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.15em]">Marketing Tools</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="w-9 h-9 flex items-center justify-center rounded-full text-gray-400 hover:bg-white hover:text-red-500 transition-all shadow-sm">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="couponForm" method="POST" class="p-8">
                @csrf
                <input type="hidden" name="_method" id="methodField">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="ml-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kode Kupon</label>
                        <input type="text" name="code" id="couponCode" required placeholder="CONTOH: PROMO20" 
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none transition-all text-sm font-bold uppercase">
                    </div>

                    <div class="space-y-1.5">
                        <label class="ml-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Kupon</label>
                        <input type="text" name="name" id="couponName" required placeholder="Nama Promo"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none transition-all text-sm font-semibold">
                    </div>

                    <div class="space-y-1.5">
                        <label class="ml-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipe Diskon</label>
                        <select name="type" id="couponType" class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none transition-all text-sm font-semibold appearance-none">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="ml-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nilai Diskon</label>
                        <input type="number" name="value" id="couponValue" required
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none transition-all text-sm font-semibold">
                    </div>

                    <div class="space-y-1.5">
                        <label class="ml-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Min. Belanja (Rp)</label>
                        <input type="number" name="min_purchase" id="couponMin" value="0"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none transition-all text-sm font-semibold">
                    </div>

                    <div class="space-y-1.5">
                        <label class="ml-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kuota Pemakaian</label>
                        <input type="number" name="quota" id="couponQuota" required
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none transition-all text-sm font-semibold">
                    </div>

                    <div class="space-y-1.5">
                        <label class="ml-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Mulai Berlaku</label>
                        <input type="date" name="started_at" id="couponStart"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none transition-all text-sm font-semibold">
                    </div>

                    <div class="space-y-1.5">
                        <label class="ml-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Berakhir Pada</label>
                        <input type="date" name="expired_at" id="couponExpire"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none transition-all text-sm font-semibold">
                    </div>

                    <div class="md:col-span-2 flex items-center gap-3 px-1 mt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="couponStatus" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-primary"></div>
                            <span class="ml-3 text-sm font-bold text-gray-600">Aktifkan Kupon</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8">
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-400 hover:bg-gray-100 transition-all">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-10 py-3 rounded-2xl bg-brand-primary text-white text-xs font-black uppercase tracking-[0.1em] shadow-xl shadow-brand-primary/20 hover:bg-brand-dark transition-all flex items-center gap-2">
                        <span id="btnText">Simpan Kupon</span>
                        <i id="loader" class="fa-solid fa-circle-notch animate-spin hidden"></i>
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
                    language: { search: "_INPUT_", searchPlaceholder: "Cari kupon..." }
                });

                const modal = $('#couponModal');
                const form = $('#couponForm');

                window.openCreateModal = function () {
                    modal.removeClass('hidden').addClass('flex');
                    $('#modalTitle').text('Buat Kupon Baru');
                    form.attr('action', '/coupons');
                    $('#methodField').val('POST');
                    form[0].reset();
                }

                window.openEditModal = function (data) {
                    modal.removeClass('hidden').addClass('flex');
                    $('#modalTitle').text('Edit Kupon');
                    form.attr('action', `/coupons/${data.id}`);
                    $('#methodField').val('PUT');

                    $('#couponCode').val(data.code);
                    $('#couponName').val(data.name);
                    $('#couponType').val(data.type);
                    $('#couponValue').val(data.value);
                    $('#couponMin').val(data.min_purchase);
                    $('#couponQuota').val(data.quota);
                    $('#couponStart').val(data.started_at ? data.started_at.split(' ')[0] : '');
                    $('#couponExpire').val(data.expired_at ? data.expired_at.split(' ')[0] : '');
                    $('#couponStatus').prop('checked', data.is_active == 1);
                }

                window.closeModal = function () { modal.addClass('hidden').removeClass('flex'); }
                modal.on('click', function(e) { if (e.target === this) closeModal(); });

                window.deleteCoupon = function (id) {
                    Swal.fire({
                        title: 'Hapus Kupon?',
                        text: "Kupon yang dihapus tidak dapat digunakan kembali.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2D5A27',
                        cancelButtonText: 'Batal',
                        confirmButtonText: 'Ya, Hapus!',
                        borderRadius: '2rem'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const delForm = $('<form>', { method: 'POST', action: `/coupons/${id}` })
                                .append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }))
                                .append($('<input>', { type: 'hidden', name: '_method', value: 'DELETE' }));
                            $('body').append(delForm);
                            delForm.submit();
                        }
                    })
                }
            });
        </script>
    @endpush
@endsection