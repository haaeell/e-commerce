@extends('layouts.customer')

@section('title', 'Checkout - Al-Hayya Hijab')

@section('content')
    <section class="py-12 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex items-center gap-4 mb-10">
                <a href="{{ route('cart.index') }}"
                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-brand-dark shadow-sm hover:bg-brand-primary hover:text-white transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl md:text-3xl font-extrabold text-brand-dark">Checkout</h1>
            </div>

            <form action="#" method="POST" id="checkoutForm">
                @csrf
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Kolom Kiri: Alamat & Pengiriman -->
                    <div class="w-full lg:w-2/3 space-y-6">

                        <!-- ALAMAT PENGIRIMAN -->
                        <div class="bg-white p-6 md:p-8 rounded-[32px] shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-soft-mint rounded-lg flex items-center justify-center text-brand-primary text-sm">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <h2 class="text-lg font-bold text-brand-dark">Alamat Pengiriman</h2>
                                </div>

                                @if($address)
                                    <button type="button" onclick="toggleAddressModal()"
                                        class="text-sm font-bold text-brand-primary hover:underline transition-all">
                                        Ganti Alamat
                                    </button>
                                @endif
                            </div>

                            @if($address)
                                <div class="relative p-5 border-2 border-brand-primary/20 bg-soft-mint/10 rounded-2xl">
                                    <div class="absolute top-4 right-4">
                                        <span
                                            class="px-2 py-1 bg-brand-primary text-[10px] text-white font-bold rounded-md uppercase">
                                            {{ $address->label }}
                                        </span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-brand-dark">{{ $address->receiver_name }}</p>
                                            <span class="text-gray-300">|</span>
                                            <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                                        </div>
                                        <p class="text-sm text-gray-600 leading-relaxed max-w-md">
                                            {{ $address->address }}, {{ $address->subdistrict }}, {{ $address->district }}
                                            <br>
                                            {{ $address->city }}, {{ $address->province }}, {{ $address->postal_code }}
                                        </p>
                                    </div>
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                </div>
                            @else
                                <div class="p-8 border-2 border-dashed border-gray-200 rounded-2xl text-center">
                                    <p class="text-gray-500 mb-4 text-sm">Anda belum memiliki alamat pengiriman.</p>
                                    <button type="button" onclick="toggleAddressModal()"
                                        class="inline-flex items-center gap-2 px-6 py-2 bg-brand-primary text-brand-dark font-bold rounded-xl text-sm transition-transform active:scale-95">
                                        <i class="fa-solid fa-plus"></i> Tambah Alamat Baru
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- PILIHAN KURIR -->
                        <div class="bg-white p-6 md:p-8 rounded-[32px] shadow-sm border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500 text-sm">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </div>
                                <h2 class="text-lg font-bold text-brand-dark">Opsi Pengiriman</h2>
                            </div>

                            <div class="grid grid-cols-3 gap-3 mb-6">
                                @foreach(['jne' => 'JNE', 'pos' => 'POS', 'tiki' => 'TIKI'] as $code => $name)
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="courier" value="{{ $code }}" class="peer sr-only">
                                        <div
                                            class="py-3 px-4 border-2 border-gray-100 rounded-2xl text-center peer-checked:border-brand-primary peer-checked:bg-soft-mint/30 transition-all">
                                            <span
                                                class="text-xs font-black text-gray-400 peer-checked:text-brand-primary uppercase tracking-widest">{{ $name }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <!-- List Layanan (Placeholder) -->
                            <div id="shipping-services" class="space-y-3">
                                <p class="text-center text-gray-400 text-xs italic py-4">Silahkan pilih kurir terlebih
                                    dahulu</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Summary -->
                    <div class="w-full lg:w-1/3">
                        <div class="bg-brand-dark text-white p-8 rounded-[40px] shadow-2xl lg:sticky lg:top-28">
                            <h2 class="text-xl font-bold mb-6">Ringkasan</h2>

                            <div class="max-h-48 overflow-y-auto no-scrollbar space-y-4 mb-8 pr-2">
                                @foreach($carts as $item)
                                    <div class="flex gap-4">
                                        <div class="w-12 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-white/10">
                                            @php $primaryImage = $item->product->images->where('is_primary', true)->first(); @endphp
                                            <img src="{{ $primaryImage ? asset('storage/' . $primaryImage->image_url) : 'https://via.placeholder.com/400x533' }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <h4 class="text-[11px] font-bold truncate">{{ $item->product->name }}</h4>
                                            @if($item->variant)
                                                <p class="text-[9px] text-brand-primary/80 font-medium mb-1">
                                                    @foreach($item->variant->attributes as $attr)
                                                        {{ $attr->attribute_value }}{{ !$loop->last ? ' | ' : '' }}
                                                    @endforeach
                                                </p>
                                            @endif
                                            <p class="text-[10px] text-white/50">
                                                {{ $item->qty }} unit x Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="space-y-3 pt-6 border-t border-white/10 mb-8">
                                <div class="flex justify-between text-xs text-white/60">
                                    <span>Subtotal Produk</span>
                                    <span
                                        class="font-bold text-white">Rp{{ number_format($total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-white/60">
                                    <span>Total Ongkos Kirim</span>
                                    <span class="font-bold text-white" id="shipping_cost_display">Rp0</span>
                                </div>
                                <div class="pt-4 flex flex-col gap-1">
                                    <span class="text-[10px] uppercase tracking-widest text-brand-primary font-bold">Total
                                        Pembayaran</span>
                                    <span class="text-3xl font-extrabold text-white"
                                        id="grand_total_display">Rp{{ number_format($total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" @if(!$address) disabled @endif
                                class="group relative w-full py-4 {{ !$address ? 'bg-gray-600 cursor-not-allowed' : 'bg-brand-primary shadow-xl hover:shadow-brand-primary/40 hover:-translate-y-1' }} text-brand-dark font-black rounded-2xl flex items-center justify-center gap-3 overflow-hidden transition-all active:scale-95">
                                <span class="relative z-10 uppercase tracking-tighter">Bayar Sekarang</span>
                                <i
                                    class="fa-solid fa-arrow-right text-xs relative z-10 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- MODAL TAMBAH ALAMAT -->
    <div id="addressModal" class="fixed inset-0 z-[99] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleAddressModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
            <div
                class="bg-white rounded-[32px] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col animate-in fade-in zoom-in duration-300">
                <div class="p-8 overflow-y-auto no-scrollbar">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-brand-dark">Alamat Pengiriman</h3>
                        <button type="button" onclick="toggleAddressModal()" class="text-gray-400 hover:text-brand-dark">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="flex gap-4 mb-6 border-b border-gray-100">
                        <button type="button" onclick="switchAddressTab('list')" id="tab-list"
                            class="pb-2 border-b-2 border-brand-primary text-brand-primary font-bold text-sm">Alamat
                            Saya</button>
                        <button type="button" onclick="switchAddressTab('new')" id="tab-new"
                            class="pb-2 border-b-2 border-transparent text-gray-400 font-bold text-sm">Tambah Baru</button>
                    </div>

                    <div id="address-list-section" class="space-y-3">
                        @forelse(auth()->user()->addresses as $item)
                            <form action="{{ route('checkout.set-address') }}" method="POST">
                                @csrf
                                <input type="hidden" name="address_id" value="{{ $item->id }}">
                                <button type="submit"
                                    class="w-full text-left p-4 border-2 {{ $address && $address->id == $item->id ? 'border-brand-primary bg-soft-mint/10' : 'border-gray-100' }} rounded-2xl hover:border-brand-primary transition-all group">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span
                                                class="text-[10px] font-bold uppercase px-2 py-0.5 bg-gray-100 rounded text-gray-500 mb-2 inline-block">{{ $item->label }}</span>
                                            <p class="font-bold text-brand-dark text-sm">{{ $item->receiver_name }} <span
                                                    class="font-normal text-gray-400">| {{ $item->phone }}</span></p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $item->address }}, {{ $item->city }}</p>
                                        </div>
                                        @if($address && $address->id == $item->id)
                                            <i class="fa-solid fa-circle-check text-brand-primary"></i>
                                        @endif
                                    </div>
                                </button>
                            </form>
                        @empty
                            <p class="text-center text-gray-400 py-10">Belum ada alamat tersimpan.</p>
                        @endforelse
                    </div>

                    <div id="address-new-section" class="hidden">
                        <form action="{{ route('addresses.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="label" placeholder="Label (Rumah/Kantor)"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-100 text-sm" required>
                                <input type="text" name="receiver_name" placeholder="Nama Penerima"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-100 text-sm" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="phone" placeholder="WhatsApp"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-100 text-sm" required>
                                <input type="text" name="postal_code" placeholder="Kode Pos"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-100 text-sm" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <select id="prov_select" class="select2-init" required>
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <select id="city_select" class="select2-init" disabled required>
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <select id="dist_select" class="select2-init" disabled required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <select id="sub_select" class="select2-init" disabled required>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>

                            <input type="hidden" name="province" id="province_name">
                            <input type="hidden" name="city" id="city_name">
                            <input type="hidden" name="district" id="district_name">
                            <input type="hidden" name="subdistrict" id="subdistrict_name">

                            <textarea name="address" rows="3" placeholder="Detail Alamat Lengkap"
                                class="w-full px-4 py-3 rounded-xl border border-gray-100 text-sm" required></textarea>

                            <button type="submit"
                                class="w-full py-4 bg-brand-primary text-brand-dark font-black rounded-xl shadow-lg">Simpan
                                & Gunakan Alamat</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleAddressModal() {
            $('#addressModal').toggleClass('hidden');
        }

        function switchAddressTab(tab) {
            if (tab === 'new') {
                $('#address-list-section').addClass('hidden');
                $('#address-new-section').removeClass('hidden');
                $('#tab-new').addClass('border-brand-primary text-brand-primary').removeClass('border-transparent text-gray-400');
                $('#tab-list').addClass('border-transparent text-gray-400').removeClass('border-brand-primary text-brand-primary');
            } else {
                $('#address-new-section').addClass('hidden');
                $('#address-list-section').removeClass('hidden');
                $('#tab-list').addClass('border-brand-primary text-brand-primary').removeClass('border-transparent text-gray-400');
                $('#tab-new').addClass('border-transparent text-gray-400').removeClass('border-brand-primary text-brand-primary');
            }
        }

        $(document).ready(function () {
            let subtotal = {{ $total_price }};
            let totalWeight = {{ $total_weight }};

            // EVENT: Saat Pilih Kurir (JNE/POS/TIKI)
            $('input[name="courier"]').on('change', function () {
                let courier = $(this).val();
                let _token = $('input[name="_token"]').val();

                // Loading state
                $('#shipping-services').html('<div class="py-6 text-center"><i class="fa-solid fa-circle-notch fa-spin text-brand-primary"></i> <span class="text-xs text-gray-400 ml-2">Mengecek ongkir...</span></div>');

                $.ajax({
                    url: "{{ route('checkout.check-ongkir') }}",
                    method: "POST",
                    data: {
                        _token: _token,
                        courier: courier,
                        weight: totalWeight
                    },
                    success: function (data) {
                        let html = '';
                        if (data.length > 0) {
                            data.forEach(service => {
                                html += `
                                        <label class="relative cursor-pointer block group">
                                            <input type="radio" name="shipping_service" value="${service.cost[0].value}" 
                                                   data-label="${service.service}" class="peer sr-only" required>
                                            <div class="p-4 border-2 border-gray-100 rounded-2xl flex justify-between items-center peer-checked:border-brand-primary peer-checked:bg-soft-mint/20 transition-all hover:border-gray-200">
                                                <div>
                                                    <p class="text-sm font-bold text-brand-dark uppercase">${service.service}</p>
                                                    <p class="text-[10px] text-gray-400">${service.description} (${service.cost[0].etd} Hari)</p>
                                                </div>
                                                <p class="text-sm font-black text-brand-primary">Rp${new Intl.NumberFormat('id-ID').format(service.cost[0].value)}</p>
                                            </div>
                                        </label>
                                    `;
                            });
                        } else {
                            html = '<p class="text-center text-red-400 text-xs py-4">Layanan kurir tidak tersedia untuk wilayah ini.</p>';
                        }
                        $('#shipping-services').html(html);
                    },
                    error: function (err) {
                        $('#shipping-services').html('<p class="text-center text-red-500 text-xs py-4">Gagal memuat ongkir. Pastikan alamat sudah benar.</p>');
                    }
                });
            });

            // EVENT: Saat Pilih Layanan (REG/OKE/YES)
            $(document).on('change', 'input[name="shipping_service"]', function () {
                let shippingCost = parseInt($(this).val());
                let grandTotal = subtotal + shippingCost;

                // Update Tampilan Summary
                $('#shipping_cost_display').text('Rp' + new Intl.NumberFormat('id-ID').format(shippingCost));
                $('#grand_total_display').text('Rp' + new Intl.NumberFormat('id-ID').format(grandTotal));
            });

            // Inisialisasi Select2
            $('.select2-init').select2({
                width: '100%',
                dropdownParent: $('#addressModal') // Penting agar select2 muncul di dalam modal
            });

            const apiBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';

            // Load Provinsi
            fetch(`${apiBase}/provinces.json`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        $('#prov_select').append(new Option(item.name, item.id));
                    });
                });

            // Event Provinsi
            $('#prov_select').on('change', function () {
                let id = $(this).val();
                let name = $("#prov_select option:selected").text();
                $('#province_name').val(name);

                resetSelect('#city_select', 'Kota/Kabupaten');
                resetSelect('#dist_select', 'Kecamatan');
                resetSelect('#sub_select', 'Kelurahan');

                if (id) {
                    fetch(`${apiBase}/regencies/${id}.json`)
                        .then(res => res.json())
                        .then(data => {
                            $('#city_select').prop('disabled', false);
                            data.forEach(item => $('#city_select').append(new Option(item.name, item.id)));
                        });
                }
            });

            // Event Kota
            $('#city_select').on('change', function () {
                let id = $(this).val();
                let name = $("#city_select option:selected").text();
                $('#city_name').val(name);

                resetSelect('#dist_select', 'Kecamatan');
                resetSelect('#sub_select', 'Kelurahan');

                if (id) {
                    fetch(`${apiBase}/districts/${id}.json`)
                        .then(res => res.json())
                        .then(data => {
                            $('#dist_select').prop('disabled', false);
                            data.forEach(item => $('#dist_select').append(new Option(item.name, item.id)));
                        });
                }
            });

            // Lanjutkan logic yang sama untuk Kecamatan dan Kelurahan...
            $('#dist_select').on('change', function () {
                let id = $(this).val();
                $('#district_name').val($("#dist_select option:selected").text());
                resetSelect('#sub_select', 'Kelurahan');
                if (id) {
                    fetch(`${apiBase}/villages/${id}.json`).then(res => res.json()).then(data => {
                        $('#sub_select').prop('disabled', false);
                        data.forEach(item => $('#sub_select').append(new Option(item.name, item.id)));
                    });
                }
            });

            $('#sub_select').on('change', function () {
                $('#subdistrict_name').val($("#sub_select option:selected").text());
            });

            function resetSelect(el, label) {
                $(el).html(`<option value="">Pilih ${label}</option>`).prop('disabled', true).trigger('change');
            }
        });
    </script>
@endsection