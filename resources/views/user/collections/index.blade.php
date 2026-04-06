@extends('layouts.customer')

@section('title', 'Semua Koleksi - Al-Hayya Hijab')

@section('content')
    <section class="py-12 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div class="space-y-2">
                    <nav class="flex text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">
                        <a href="/" class="hover:text-brand-primary">Beranda</a>
                        <span class="mx-2 text-gray-300">/</span>
                        <span class="text-brand-dark">Koleksi Lengkap</span>
                    </nav>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-brand-dark tracking-tight">Eksplorasi <span
                            class="text-brand-primary">Koleksi</span></h1>
                    <p class="text-gray-500 text-sm md:text-base">Menampilkan {{ $products->count() }} produk hijab pilihan
                        terbaik untukmu.</p>
                </div>

                <form action="{{ route('collections.index') }}" method="GET" class="relative group w-full md:w-96">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari hijab favoritmu..."
                        class="w-full pl-12 pr-6 py-4 bg-white border border-gray-100 rounded-[24px] shadow-sm focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary outline-none transition-all group-hover:shadow-md text-sm">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-brand-primary transition-colors"></i>
                </form>
            </div>

            <div class="flex flex-col lg:flex-row gap-10">
                <aside class="w-full lg:w-64 space-y-8 flex-shrink-0">
                    <div>
                        <h3
                            class="text-sm font-black text-brand-dark uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="w-1.5 h-4 bg-brand-primary rounded-full"></span>
                            Kategori
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('collections.index') }}"
                                class="flex items-center justify-between p-3 rounded-2xl transition-all {{ !request('category') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'bg-white text-gray-500 hover:bg-soft-mint hover:text-brand-dark' }}">
                                <span class="text-sm font-bold">Semua Produk</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('collections.index', ['category' => $cat->slug]) }}"
                                    class="flex items-center justify-between p-3 rounded-2xl transition-all {{ request('category') == $cat->slug ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'bg-white text-gray-500 hover:bg-soft-mint hover:text-brand-dark' }}">
                                    <span class="text-sm font-bold">{{ $cat->name }}</span>
                                    <span
                                        class="text-[10px] opacity-60 bg-black/5 px-2 py-0.5 rounded-full">{{ $cat->products_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-brand-dark rounded-[32px] p-6 text-white relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-brand-primary/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <p class="text-[10px] font-bold text-brand-secondary uppercase mb-2">Member Baru?</p>
                        <h4 class="text-lg font-bold leading-tight mb-4">Dapatkan Diskon 15% untuk koleksi pertama.</h4>
                        <a href="/register"
                            class="text-xs font-black text-brand-primary hover:text-white transition-colors">Daftar Sekarang
                            <i class="fa-solid fa-arrow-right ml-1"></i></a>
                    </div>
                </aside>

                <div class="flex-grow">
                    @if($products->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8">
                            @foreach($products as $product)
                                @include('user.components.product-card', ['product' => $product, 'isFlashSale' => $product->discount_price > 0])
                            @endforeach
                        </div>

                        <div class="mt-16">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-[40px] py-20 text-center border-2 border-dashed border-gray-100">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fa-solid fa-magnifying-glass text-gray-300 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-brand-dark mb-2">Produk Tidak Ditemukan</h3>
                            <p class="text-gray-400 text-sm max-w-xs mx-auto mb-8">Maaf, kami tidak menemukan produk yang Anda
                                cari. Coba kata kunci lain atau pilih kategori yang tersedia.</p>
                            <a href="{{ route('collections.index') }}"
                                class="text-brand-primary font-bold hover:underline">Lihat
                                Semua Produk</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection