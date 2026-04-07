@extends('layouts.customer')

@section('title', $product->name . ' - Al-Hayya Hijab')

@section('content')
    <section class="py-12 bg-white px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <nav class="flex text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">
                <a href="/" class="hover:text-brand-primary transition-colors">Beranda</a>
                <span class="mx-2">/</span>
                <a href="{{ route('collections.index') }}" class="hover:text-brand-primary transition-colors">Koleksi</a>
                <span class="mx-2">/</span>
                <span class="text-brand-dark">{{ $product->name }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row gap-12">
                <div class="w-full lg:w-1/2 space-y-4">
                    <div
                        class="relative aspect-[3/4] max-h-[550px] mx-auto rounded-[40px] overflow-hidden bg-gray-50 border border-gray-100 shadow-sm">
                        @php $primaryImage = $product->images->where('is_primary', true)->first(); @endphp
                        <img id="mainImage"
                            src="{{ asset('storage/' . ($primaryImage ? $primaryImage->image_url : 'default.jpg')) }}"
                            class="w-full h-full object-cover transition-all duration-500 hover:scale-105">

                        @if($product->compare_price > $product->price)
                            <div
                                class="absolute top-6 left-6 px-4 py-2 bg-red-500 text-white text-xs font-black rounded-full shadow-lg">
                                SALE
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-center gap-3 overflow-x-auto no-scrollbar pb-2">
                        @foreach($product->images as $img)
                            <button onclick="changeImage('{{ asset('storage/' . $img->image_url) }}')"
                                class="flex-none w-16 h-16 rounded-xl overflow-hidden border-2 {{ $img->is_primary ? 'border-brand-primary' : 'border-transparent' }} hover:border-brand-primary transition-all shadow-sm">
                                <img src="{{ asset('storage/' . $img->image_url) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="w-full lg:w-1/2 space-y-8">
                    <div>
                        <span
                            class="text-brand-primary font-bold text-xs uppercase tracking-[0.2em] mb-2 block">{{ $product->category->name }}</span>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-brand-dark leading-tight">{{ $product->name }}
                        </h1>

                        <div class="flex items-center gap-4 mt-4">
                            <div id="displayPrice"
                                class="flex items-center text-brand-dark font-black text-2xl md:text-3xl">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </div>
                            @if($product->compare_price > $product->price)
                                <div class="text-gray-300 line-through font-bold text-lg">
                                    Rp{{ number_format($product->compare_price, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 w-full"></div>

                    <div class="prose prose-sm max-w-none text-gray-500 leading-relaxed">
                        <p class="font-bold text-brand-dark text-sm uppercase tracking-widest mb-2">Deskripsi Produk</p>
                        <div class="text-gray-600">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <form id="addToCartForm" class="space-y-8">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_variant_id" id="selectedVariantId">

                        @if($product->has_variant && $product->variants->count() > 0)
                            @php
                                $groupedAttributes = [];
                                foreach ($product->variants as $variant) {
                                    foreach ($variant->attributes as $attr) {
                                        $groupedAttributes[$attr->attribute_name][] = $attr->attribute_value;
                                    }
                                }
                                foreach ($groupedAttributes as $name => $values) {
                                    $groupedAttributes[$name] = array_unique($values);
                                }
                            @endphp

                            <div class="space-y-6" id="variantSelection">
                                @foreach($groupedAttributes as $attrName => $values)
                                    <div class="variant-group">
                                        <p class="font-bold text-brand-dark text-xs uppercase tracking-widest mb-3">{{ $attrName }}
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($values as $value)
                                                <button type="button" data-type="{{ $attrName }}" data-value="{{ $value }}"
                                                    class="variant-btn px-4 py-2 border-2 border-gray-100 rounded-xl text-sm font-bold text-gray-500 hover:border-brand-primary transition-all">
                                                    {{ $value }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-6">
                            <div class="flex items-center bg-gray-50 rounded-2xl border border-gray-100 p-1">
                                <button type="button" onclick="adjustQty(-1)"
                                    class="w-10 h-10 flex items-center justify-center text-brand-dark hover:bg-white rounded-xl transition-all shadow-sm">-</button>
                                <input type="number" name="quantity" id="qtyInput" value="1" min="1"
                                    max="{{ $product->stock }}"
                                    class="w-12 text-center bg-transparent font-bold text-brand-dark outline-none">
                                <button type="button" onclick="adjustQty(1)"
                                    class="w-10 h-10 flex items-center justify-center text-brand-dark hover:bg-white rounded-xl transition-all shadow-sm">+</button>
                            </div>
                            <p class="text-xs font-bold text-gray-400">Stok: <span class="text-brand-dark"
                                    id="productStock">{{ $product->stock }}</span></p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit" id="btnAddToCart"
                                class="flex-grow py-4 bg-brand-primary text-white font-bold rounded-[20px] shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/40 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-sm">
                                <i class="fa-solid fa-cart-plus"></i>
                                <span id="btnText">Tambah ke Keranjang</span>
                            </button>
                            <button type="button"
                                class="px-6 py-4 bg-white text-brand-dark border-2 border-brand-dark/10 font-bold rounded-[20px] hover:bg-brand-dark hover:text-white transition-all active:scale-95">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </div>
                    </form>

                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-soft-mint/50 border border-brand-primary/10">
                            <i class="fa-solid fa-truck-fast text-brand-primary text-lg"></i>
                            <span class="text-[10px] font-bold text-brand-dark uppercase">Pengiriman Cepat</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-soft-blue/50 border border-blue-100">
                            <i class="fa-solid fa-shield-check text-blue-500 text-lg"></i>
                            <span class="text-[10px] font-bold text-brand-dark uppercase">Kualitas Terjamin</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($relatedProducts->count() > 0)
                <div class="mt-24">
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-8">Produk <span
                            class="text-brand-primary">Terkait</span></h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $rel)
                            @include('user.components.product-card', ['product' => $rel, 'isFlashSale' => $rel->compare_price > $rel->price])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const variants = @json($product->variants->load('attributes'));
        let selectedChoices = {};

        function changeImage(src) {
            $('#mainImage').fadeOut(200, function () {
                $(this).attr('src', src).fadeIn(200);
            });
        }

        function adjustQty(val) {
            let input = $('#qtyInput');
            let stock = parseInt($('#productStock').text());
            let current = parseInt(input.val());
            let next = current + val;
            if (next >= 1 && next <= stock) input.val(next);
        }

        function findMatchingVariant() {
            const match = variants.find(v => {
                return v.attributes.every(attr => selectedChoices[attr.attribute_name] === attr.attribute_value);
            });

            if (match) {
                $('#selectedVariantId').val(match.id);
                $('#displayPrice').text('Rp' + new Intl.NumberFormat('id-ID').format(match.price));
                $('#productStock').text(match.stock);
                $('#qtyInput').attr('max', match.stock);
                if (parseInt($('#qtyInput').val()) > match.stock) $('#qtyInput').val(match.stock);

                if (match.stock <= 0) {
                    $('#btnAddToCart').prop('disabled', true).addClass('opacity-50 text-gray-300 pointer-events-none');
                    $('#btnText').text('Stok Habis');
                } else {
                    $('#btnAddToCart').prop('disabled', false).removeClass('opacity-50 text-gray-300 pointer-events-none');
                    $('#btnText').text('Tambah ke Keranjang');
                }
            }
        }

        $(document).ready(function () {
            $('.variant-btn').on('click', function () {
                const type = $(this).data('type');
                const value = $(this).data('value');

                $(this).closest('.variant-group').find('.variant-btn')
                    .removeClass('border-brand-primary bg-soft-mint text-brand-primary')
                    .addClass('border-gray-100 text-gray-500');

                $(this).addClass('border-brand-primary bg-soft-mint text-brand-primary')
                    .removeClass('border-gray-100 text-gray-500');

                selectedChoices[type] = value;
                if (Object.keys(selectedChoices).length === $('.variant-group').length) {
                    findMatchingVariant();
                }
            });

            $('#addToCartForm').on('submit', function (e) {
                e.preventDefault();

                @if($product->has_variant)
                    if (!$('#selectedVariantId').val()) {
                        Swal.fire({ icon: 'warning', title: 'Pilih Varian', text: 'Silakan pilih warna/ukuran terlebih dahulu.' });
                        return;
                    }
                @endif

                @if(!Auth::check())
                    window.location.href = "{{ route('login') }}";
                    return;
                @endif

                let btn = $('#btnAddToCart');
                btn.prop('disabled', true).addClass('opacity-70');

                $.ajax({
                    url: "{{ route('cart.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: false, timer: 1500 });
                            updateCartCount();
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal menambah ke keranjang.' });
                    },
                    complete: function () {
                        btn.prop('disabled', false).removeClass('opacity-70');
                    }
                });
            });

            function updateCartCount() {
                $.ajax({
                    url: "{{ route('home') }}",
                    method: "GET",
                    success: function (data) {
                        let newCount = $(data).find('#navbar-cart-count').text();
                        $('#navbar-cart-count').text(newCount);
                    }
                });
            }
        });
    </script>
@endpush