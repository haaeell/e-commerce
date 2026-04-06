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
                <div class="relative aspect-[3/4] rounded-[40px] overflow-hidden bg-gray-50 border border-gray-100 shadow-sm">
                    @php $primaryImage = $product->images->where('is_primary', true)->first(); @endphp
                    <img id="mainImage" src="{{ asset('storage/' . ($primaryImage ? $primaryImage->image_url : 'default.jpg')) }}" 
                        class="w-full h-full object-cover transition-all duration-500 hover:scale-105">
                    
                    @if($product->compare_price > $product->price)
                        <div class="absolute top-6 left-6 px-4 py-2 bg-red-500 text-white text-xs font-black rounded-full shadow-lg">
                            SALE
                        </div>
                    @endif
                </div>

                <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
                    @foreach($product->images as $img)
                    <button onclick="changeImage('{{ asset('storage/' . $img->image_url) }}')" 
                        class="flex-none w-20 h-20 rounded-2xl overflow-hidden border-2 {{ $img->is_primary ? 'border-brand-primary' : 'border-transparent' }} hover:border-brand-primary transition-all shadow-sm">
                        <img src="{{ asset('storage/' . $img->image_url) }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="w-full lg:w-1/2 space-y-8">
                <div>
                    <span class="text-brand-primary font-bold text-xs uppercase tracking-[0.2em] mb-2 block">{{ $product->category->name }}</span>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-brand-dark leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mt-4">
                        <div class="flex items-center text-brand-dark font-black text-2xl md:text-3xl">
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

                <div class="prose prose-sm text-gray-500 leading-relaxed">
                    <p class="font-bold text-brand-dark text-sm uppercase tracking-widest mb-2">Deskripsi Produk</p>
                    {!! nl2br(e($product->description)) !!}
                </div>

                <form action="{{ route('cart.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center gap-4">
                        <div class="flex items-center bg-gray-50 rounded-2xl border border-gray-100 p-1">
                            <button type="button" onclick="adjustQty(-1)" class="w-10 h-10 flex items-center justify-center text-brand-dark hover:bg-white rounded-xl transition-all shadow-sm">-</button>
                            <input type="number" name="quantity" id="qtyInput" value="1" min="1" class="w-12 text-center bg-transparent font-bold text-brand-dark outline-none">
                            <button type="button" onclick="adjustQty(1)" class="w-10 h-10 flex items-center justify-center text-brand-dark hover:bg-white rounded-xl transition-all shadow-sm">+</button>
                        </div>
                        <p class="text-xs font-bold text-gray-400">Tersedia: <span class="text-brand-dark">{{ $product->stock }}</span> Stok</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="flex-grow py-4 bg-brand-primary text-white font-bold rounded-[20px] shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/40 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-sm">
                            <i class="fa-solid fa-cart-plus"></i>
                            Tambah ke Keranjang
                        </button>
                        <button type="button" class="px-6 py-4 bg-white text-brand-dark border-2 border-brand-dark/10 font-bold rounded-[20px] hover:bg-brand-dark hover:text-white transition-all active:scale-95">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </div>
                </form>

                <div class="grid grid-cols-2 gap-4">
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
            <h2 class="text-2xl font-extrabold text-brand-dark mb-8">Produk <span class="text-brand-primary">Terkait</span></h2>
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

@section('scripts')
<script>
    function changeImage(src) {
        $('#mainImage').fadeOut(200, function() {
            $(this).attr('src', src).fadeIn(200);
        });
    }

    function adjustQty(val) {
        let input = $('#qtyInput');
        let current = parseInt(input.val());
        let next = current + val;
        if (next >= 1) input.val(next);
    }
</script>
@endsection