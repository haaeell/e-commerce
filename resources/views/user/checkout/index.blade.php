@extends('layouts.customer')

@section('title', 'Checkout - Al-Hayya Hijab')

@section('content')
    <section class="py-12 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
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

                    <div class="w-full lg:w-2/3 space-y-6">

                        {{-- ALAMAT PENGIRIMAN --}}
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
                                            {{ $address->address }}, {{ $address->subdistrict }}, {{ $address->district }}<br>
                                            {{ $address->city }}, {{ $address->province }}, {{ $address->postal_code }}
                                        </p>
                                        {{-- @if(!$address->rajaongkir_destination_id)
                                        <div
                                            class="flex items-center gap-2 mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                                            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xs"></i>
                                            <p class="text-xs text-amber-700">
                                                Alamat ini belum memiliki data destinasi pengiriman.
                                                <a href="{{ route('addresses.edit', $address->id) }}"
                                                    class="font-bold underline">Edit alamat</a> dan pilih ulang lokasi.
                                            </p>
                                        </div>
                                        @endif --}}
                                    </div>
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                </div>
                            @else
                                <div class="p-8 border-2 border-dashed border-gray-200 rounded-2xl text-center">
                                    <div
                                        class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fa-solid fa-location-dot text-gray-300 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500 mb-4 text-sm">Anda belum memiliki alamat pengiriman.</p>
                                    <button type="button" onclick="toggleAddressModal()"
                                        class="inline-flex items-center gap-2 px-6 py-2 bg-brand-primary text-brand-dark font-bold rounded-xl text-sm transition-transform active:scale-95">
                                        <i class="fa-solid fa-plus"></i> Tambah Alamat Baru
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- OPSI PENGIRIMAN --}}
                        <div class="bg-white p-6 md:p-8 rounded-[32px] shadow-sm border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500 text-sm">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </div>
                                <h2 class="text-lg font-bold text-brand-dark">Opsi Pengiriman</h2>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-2">
                                @foreach($couriers as $code => $name)
                                    <label class="relative cursor-pointer">
                                        <input type="checkbox" name="courier_check[]" value="{{ $code }}"
                                            class="courier-checkbox peer sr-only">
                                        <div
                                            class="py-3 px-4 border-2 border-gray-100 rounded-2xl text-center
                                                                                                                                                                                    peer-checked:border-brand-primary peer-checked:bg-soft-mint/30
                                                                                                                                                                                    transition-all cursor-pointer select-none">
                                            <span
                                                class="text-xs font-black text-gray-400 peer-checked:text-brand-primary uppercase tracking-widest">
                                                {{ $name }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-gray-400 mb-4">Pilih satu atau lebih kurir, lalu klik "Cek Ongkir"
                            </p>

                            <button type="button" id="btn-cek-ongkir"
                                class="w-full py-3 bg-brand-primary text-brand-dark font-black rounded-xl text-sm
                                                                                                       hover:-translate-y-0.5 transition-all active:scale-95">
                                <i class="fa-solid fa-magnifying-glass mr-2"></i> Cek Ongkir
                            </button>

                            <div id="shipping-services" class="mt-5 space-y-3">
                                <p class="text-center text-gray-400 text-xs italic py-4">Pilih kurir lalu klik Cek Ongkir
                                </p>
                            </div>

                            <input type="hidden" name="courier_code" id="selected_courier_code">
                            <input type="hidden" name="courier_service" id="selected_courier_service">
                            <input type="hidden" name="shipping_cost" id="selected_shipping_cost">
                            <input type="hidden" name="shipping_etd" id="selected_shipping_etd">
                        </div>

                    </div>

                    {{-- RINGKASAN --}}
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
                                <div id="selected_service_info" class="hidden">
                                    <div class="flex justify-between text-xs text-white/40">
                                        <span id="selected_service_label">-</span>
                                        <span id="selected_service_etd">-</span>
                                    </div>
                                </div>

                                <div id="cheapest-shipping-highlight"
                                    class="hidden mt-2 p-2 bg-green-50 border border-green-200 rounded-xl">
                                    <p class="text-[10px] font-bold text-green-700 flex items-center gap-1">
                                        <i class="fa-solid fa-crown text-yellow-500"></i>
                                        Ini ongkir termurah untuk rute ini!
                                    </p>
                                    </p>
                                </div>
                                <div class="pt-4 flex flex-col gap-1">
                                    <span class="text-[10px] uppercase tracking-widest text-brand-primary font-bold">Total
                                        Pembayaran</span>
                                    <span class="text-3xl font-extrabold text-white" id="grand_total_display">
                                        Rp{{ number_format($total_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <button type="submit" id="btn-submit" @if(!$address || !$address->rajaongkir_destination_id)
                            disabled @endif
                                class="group relative w-full py-4 bg-brand-primary text-brand-dark font-black rounded-2xl
                                                                                                       flex items-center justify-center gap-3 overflow-hidden transition-all active:scale-95
                                                                                                       shadow-xl hover:shadow-brand-primary/40 hover:-translate-y-1
                                                                                                       disabled:bg-gray-600 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none">
                                <span class="relative z-10 uppercase tracking-tighter">Bayar Sekarang</span>
                                <i
                                    class="fa-solid fa-arrow-right text-xs relative z-10 group-hover:translate-x-1 transition-transform"></i>
                            </button>

                            @if(!$address)
                                <p class="text-center text-white/40 text-[10px] mt-3">Tambahkan alamat pengiriman terlebih
                                    dahulu</p>
                            @elseif(!$address->rajaongkir_destination_id)
                                <p class="text-center text-amber-400 text-[10px] mt-3">Lengkapi data alamat untuk melanjutkan
                                </p>
                            @endif
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </section>

    {{-- MODAL ALAMAT --}}
    <div id="addressModal" class="fixed inset-0 z-[99] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleAddressModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
            <div class="bg-white rounded-[32px] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
                <div class="p-8 overflow-y-auto no-scrollbar">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-brand-dark">Alamat Pengiriman</h3>
                        <button type="button" onclick="toggleAddressModal()" class="text-gray-400 hover:text-brand-dark">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="flex gap-4 mb-6 border-b border-gray-100">
                        <button type="button" onclick="switchAddressTab('list')" id="tab-list"
                            class="pb-3 border-b-2 border-brand-primary text-brand-primary font-bold text-sm -mb-px">
                            Alamat Saya
                        </button>
                        <button type="button" onclick="switchAddressTab('new')" id="tab-new"
                            class="pb-3 border-b-2 border-transparent text-gray-400 font-bold text-sm -mb-px">
                            Tambah Baru
                        </button>
                    </div>

                    {{-- LIST ALAMAT --}}
                    <div id="address-list-section" class="space-y-3">
                        @forelse(auth()->user()->addresses as $item)
                            <form action="{{ route('checkout.set-address') }}" method="POST">
                                @csrf
                                <input type="hidden" name="address_id" value="{{ $item->id }}">
                                <button type="submit"
                                    class="w-full text-left p-4 border-2 {{ $address && $address->id == $item->id ? 'border-brand-primary bg-soft-mint/10' : 'border-gray-100' }} rounded-2xl hover:border-brand-primary transition-all">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1 min-w-0">
                                            <span
                                                class="text-[10px] font-bold uppercase px-2 py-0.5 bg-gray-100 rounded text-gray-500 mb-2 inline-block">
                                                {{ $item->label }}
                                            </span>
                                            @if(!$item->rajaongkir_destination_id)
                                                <span
                                                    class="text-[10px] font-bold uppercase px-2 py-0.5 bg-amber-100 rounded text-amber-600 mb-2 inline-block ml-1">
                                                    Perlu diperbarui
                                                </span>
                                            @endif
                                            <p class="font-bold text-brand-dark text-sm">
                                                {{ $item->receiver_name }}
                                                <span class="font-normal text-gray-400">| {{ $item->phone }}</span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $item->address }}, {{ $item->city }}</p>
                                        </div>
                                        @if($address && $address->id == $item->id)
                                            <i class="fa-solid fa-circle-check text-brand-primary ml-3 flex-shrink-0"></i>
                                        @endif
                                    </div>
                                </button>
                            </form>
                        @empty
                            <div class="text-center py-10">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-location-dot text-gray-300 text-2xl"></i>
                                </div>
                                <p class="text-gray-400 text-sm">Belum ada alamat tersimpan.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- FORM TAMBAH ALAMAT BARU --}}
                    <div id="address-new-section" class="hidden">
                        <form action="{{ route('addresses.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 mb-1 block">Label Alamat</label>
                                    <input type="text" name="label" placeholder="Contoh: Rumah, Kantor"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-primary transition-colors"
                                        required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 mb-1 block">Nama Penerima</label>
                                    <input type="text" name="receiver_name" placeholder="Nama lengkap penerima"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-primary transition-colors"
                                        required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 mb-1 block">Nomor WhatsApp</label>
                                    <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-primary transition-colors"
                                        required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 mb-1 block">Kode Pos</label>
                                    <input type="text" name="postal_code" id="new_postal_code" placeholder="Terisi otomatis"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-primary transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">Cari Lokasi Tujuan</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300">
                                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                                    </div>
                                    <input type="text" id="destination_search"
                                        placeholder="Ketik nama kelurahan, kecamatan, atau kota..." autocomplete="off"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-primary transition-colors">
                                    <div id="destination_results"
                                        class="absolute z-50 w-full bg-white border border-gray-100 rounded-xl shadow-xl mt-1 max-h-52 overflow-y-auto hidden divide-y divide-gray-50">
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Minimal 3 karakter. Provinsi, kota, kecamatan akan
                                    terisi otomatis.</p>
                            </div>

                            <div id="dest_preview"
                                class="hidden p-3 bg-soft-mint/20 border border-brand-primary/20 rounded-xl">
                                <div class="flex items-start gap-2">
                                    <i class="fa-solid fa-circle-check text-brand-primary text-sm mt-0.5"></i>
                                    <div>
                                        <p class="text-xs font-bold text-brand-dark" id="dest_preview_label"></p>
                                        <p class="text-[10px] text-gray-500" id="dest_preview_detail"></p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="rajaongkir_destination_id" id="dest_id">
                            <input type="hidden" name="province" id="dest_province">
                            <input type="hidden" name="city" id="dest_city">
                            <input type="hidden" name="district" id="dest_district">
                            <input type="hidden" name="subdistrict" id="dest_subdistrict">

                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">Detail Alamat Lengkap</label>
                                <textarea name="address" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, patokan..."
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-primary transition-colors resize-none"
                                    required></textarea>
                            </div>

                            <button type="submit" id="btn-save-address" disabled
                                class="w-full py-4 bg-brand-primary text-brand-dark font-black rounded-xl shadow-lg
                                                                                                       transition-all active:scale-95
                                                                                                       disabled:opacity-40 disabled:cursor-not-allowed disabled:active:scale-100">
                                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan & Gunakan Alamat
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            const subtotal = {{ $total_price }};
            const totalWeight = {{ $total_weight }};
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            window.toggleAddressModal = function () {
                $('#addressModal').toggleClass('hidden');
                $('body').toggleClass('overflow-hidden');
            };

            window.switchAddressTab = function (tab) {
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
            };

            let searchTimer;
            $('#destination_search').on('input', function () {
                const query = $(this).val().trim();
                clearTimeout(searchTimer);
                if (query.length < 3) {
                    $('#destination_results').addClass('hidden').empty();
                    return;
                }
                searchTimer = setTimeout(function () {
                    $('#destination_results').removeClass('hidden').html(
                        '<div class="px-4 py-3 text-xs text-gray-400 flex items-center gap-2"><i class="fa-solid fa-circle-notch fa-spin"></i> Mencari lokasi...</div>'
                    );
                    $.ajax({
                        url: "{{ route('checkout.search-destination') }}",
                        method: 'GET',
                        data: { search: query },
                        success: function (results) {
                            const $list = $('#destination_results').empty();
                            if (!results || results.length === 0) {
                                $list.html('<div class="px-4 py-3 text-xs text-gray-400">Lokasi tidak ditemukan.</div>');
                                return;
                            }
                            results.forEach(function (item) {
                                $list.append(`
                                        <div class="px-4 py-3 hover:bg-soft-mint/20 cursor-pointer transition-colors"
                                             data-id="${item.id}" data-province="${item.province_name}"
                                             data-city="${item.city_name}" data-district="${item.district_name}"
                                             data-subdistrict="${item.subdistrict_name}" data-zipcode="${item.zip_code}"
                                             data-label="${item.label}">
                                            <p class="font-bold text-brand-dark text-xs">${item.subdistrict_name}, ${item.district_name}</p>
                                            <p class="text-[10px] text-gray-400">${item.city_name}, ${item.province_name} ${item.zip_code}</p>
                                        </div>
                                    `);
                            });
                        }
                    });
                }, 400);
            });

            $(document).on('click', '#destination_results > div[data-id]', function () {
                const id = $(this).data('id');
                const province = $(this).data('province');
                const city = $(this).data('city');
                const district = $(this).data('district');
                const subdistrict = $(this).data('subdistrict');
                const zipcode = $(this).data('zipcode');

                $('#dest_id').val(id);
                $('#dest_province').val(province);
                $('#dest_city').val(city);
                $('#dest_district').val(district);
                $('#dest_subdistrict').val(subdistrict);
                $('#new_postal_code').val(zipcode);

                $('#destination_search').val(`${subdistrict}, ${district}`);
                $('#destination_results').addClass('hidden').empty();

                $('#dest_preview_label').text('Lokasi valid');
                $('#dest_preview_detail').text(`Kode Pos: ${zipcode}`);
                $('#dest_preview').removeClass('hidden');
                $('#btn-save-address').prop('disabled', false);
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('#destination_search, #destination_results').length) {
                    $('#destination_results').addClass('hidden');
                }
            });


            let isCheckingOngkir = false;
            let currentServices = [];

            $('.courier-checkbox').on('change', function () {
                const selectedCouriers = $('.courier-checkbox:checked').map(function () {
                    return $(this).val();
                }).get();

                if (selectedCouriers.length > 0) {
                    instantCheckOngkir(selectedCouriers);
                } else {
                    resetShippingDisplay();
                }
            });

            function instantCheckOngkir(couriers) {
                if (isCheckingOngkir) return;

                isCheckingOngkir = true;
                showLoading('Mengecek ongkir secara instan...');

                $.ajax({
                    url: "{{ route('checkout.check-ongkir') }}",
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        couriers: couriers,
                        weight: totalWeight,
                    },
                    success: function (services) {
                        currentServices = services;
                        displayServices(services);
                        isCheckingOngkir = false;
                    },
                    error: function () {
                        showShippingMessage('error', 'Gagal cek ongkir. Coba lagi.');
                        isCheckingOngkir = false;
                    }
                });
            }

            function displayServices(services) {
                if (!services || services.length === 0) {
                    showShippingMessage('error', 'Tidak ada layanan untuk kurir ini.');
                    return;
                }

                // **AUTO SORT TERMURAH**
                services.sort((a, b) => parseInt(a.cost) - parseInt(b.cost));

                let html = `
                                    <div class="flex items-center justify-between mb-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-bolt text-emerald-500"></i>
                                            <p class="text-xs font-bold text-emerald-700">
                                                <span id="instant-total">${services.length}</span> ongkir tersedia 
                                                <span class="text-emerald-600">(mulai Rp${new Intl.NumberFormat('id-ID').format(services[0].cost)})</span>
                                            </p>
                                        </div>
                                    </div>
                                `;

                services.forEach(function (svc, index) {
                    const cost = parseInt(svc.cost);
                    const isCheapest = index === 0;

                    html += `
                                        <label class="instant-shipping-option block cursor-pointer transition-all hover:scale-[1.02]">
                                            <input type="radio" name="shipping_service_radio" value="${cost}"
                                                   data-code="${svc.code}" data-service="${svc.service}" 
                                                   data-name="${svc.name}" data-etd="${svc.etd}"
                                                   class="peer sr-only shipping-option ${isCheapest ? 'checked' : ''}">
                                            <div class="group relative p-4 border-2 rounded-2xl flex justify-between items-center
                                                        ${isCheapest ? 'border-emerald-400 bg-emerald-50/50 shadow-lg ring-2 ring-emerald-200/50' : 'border-gray-100 hover:border-gray-200'}
                                                        peer-checked:border-brand-primary peer-checked:bg-soft-mint/30">

                                                ${isCheapest ? `
                                                <div class="absolute -top-2 -right-2 bg-emerald-500 text-white px-2 py-1 rounded-full text-[9px] font-bold shadow-lg animate-pulse">
                                                    💰 TERMUDAH
                                                </div>
                                                ` : ''}

                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-sm font-bold text-brand-dark">${svc.name}</span>
                                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">${svc.service}</span>
                                                    </div>
                                                    <p class="text-[10px] text-gray-500">${svc.description}</p>
                                                    <p class="text-[10px] font-medium text-emerald-600 mt-1">Est. ${svc.etd} hari</p>
                                                </div>

                                                <div class="text-right ml-3">
                                                    <p class="text-lg font-black ${isCheapest ? 'text-emerald-600 drop-shadow-sm' : 'text-brand-primary'}">
                                                        Rp${new Intl.NumberFormat('id-ID').format(cost)}
                                                    </p>
                                                    ${isCheapest ? '<p class="text-[9px] text-emerald-600 font-bold mt-0.5">Paling murah!</p>' : ''}
                                                </div>
                                            </div>
                                        </label>
                                    `;
                });

                $('#shipping-services').html(html);
                $('.shipping-option:checked').trigger('change');
            }

            function resetShippingDisplay() {
                $('#shipping-services').html(`
                                    <div class="text-center py-8">
                                        <i class="fa-solid fa-truck text-gray-300 text-3xl mb-3"></i>
                                        <p class="text-sm text-gray-500 mb-2">Pilih kurir untuk melihat ongkir secara instan</p>
                                        <p class="text-xs text-gray-400">Klik kurir di atas → ongkir muncul otomatis ✨</p>
                                    </div>
                                `);
                $('#selected_shipping_cost').val('');
                updateTotals(0);
                $('#selected_service_info').addClass('hidden');
            }

            function showLoading(message) {
                $('#shipping-services').html(`
                                    <div class="flex flex-col items-center justify-center py-12 text-center">
                                        <div class="w-12 h-12 border-4 border-brand-primary/20 border-t-brand-primary rounded-full animate-spin mb-4"></div>
                                        <p class="text-sm text-gray-600">${message}</p>
                                    </div>
                                `);
            }

            function updateTotals(shippingCost) {
                const grandTotal = subtotal + shippingCost;
                $('#shipping_cost_display').text('Rp' + new Intl.NumberFormat('id-ID').format(shippingCost));
                $('#grand_total_display').text('Rp' + new Intl.NumberFormat('id-ID').format(grandTotal));
            }

            $(document).on('change', '.shipping-option', function () {
                const cost = parseInt($(this).val()) || 0;
                const code = $(this).data('code');
                const service = $(this).data('service');
                const name = $(this).data('name');
                const etd = $(this).data('etd');

                $('#selected_courier_code').val(code);
                $('#selected_courier_service').val(service);
                $('#selected_shipping_cost').val(cost);
                $('#selected_shipping_etd').val(etd);

                updateTotals(cost);

                $('#selected_service_label').text(`${name} ${service}`);
                $('#selected_service_etd').text(`Est. ${etd} hari`);
                $('#selected_service_info').removeClass('hidden');

                $('#btn-submit').prop('disabled', false);
            });


            $('#btn-cek-ongkir').prop('disabled', true).html(`
                                <i class="fa-solid fa-bolt text-emerald-500 mr-2"></i>
                                <span class="font-bold">INSTANT</span>
                                <span class="text-xs ml-1">(Auto)</span>
                            `).addClass('bg-emerald-50 border-emerald-200 text-emerald-700 cursor-default');

            $('#checkoutForm').on('submit', function (e) {
                if (!$('#selected_shipping_cost').val()) {
                    e.preventDefault();
                    showShippingMessage('warning', 'Pilih layanan pengiriman!');
                    return false;
                }
            });

            function showShippingMessage(type, message) {
                const colors = {
                    error: 'bg-red-50 border-red-200 text-red-700',
                    warning: 'bg-amber-50 border-amber-200 text-amber-700'
                };
                $('#shipping-services').html(`
                                    <div class="p-6 border-2 rounded-2xl ${colors[type] || 'bg-blue-50 border-blue-200 text-blue-700'} flex items-center gap-3">
                                        <i class="fa-solid fa-${type === 'error' ? 'xmark-circle' : 'triangle-exclamation'}"></i>
                                        <p class="text-sm">${message}</p>
                                    </div>
                                `);
            }
        });
    </script>
@endsection