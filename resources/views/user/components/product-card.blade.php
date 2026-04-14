@props(['product', 'isFlashSale' => false])

@php
    $displayPrice = $product->has_variant && $product->variants->count() > 0
        ? $product->variants->first()->price
        : $product->price;

    $displayComparePrice = $product->has_variant && $product->variants->count() > 0
        ? $product->variants->first()->compare_price
        : $product->compare_price;

    $hasDiscount = $displayComparePrice > $displayPrice;
    $diskon = $hasDiscount ? round((($displayComparePrice - $displayPrice) / $displayComparePrice) * 100) : 0;

    $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
@endphp

<a href="{{ route('collections.show', $product->slug) }}" class="product-card group block h-full">
    <div
        class="bg-white p-2 md:p-3 rounded-2xl md:rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-transparent hover:border-brand-primary/10 flex flex-col h-full relative overflow-hidden">

        @if($isFlashSale || $hasDiscount)
            <div
                class="absolute top-2.5 left-2.5 z-10 bg-red-500 text-white text-[8px] md:text-[10px] font-bold px-2 py-1 rounded-full flex items-center gap-1 shadow-sm shadow-red-500/20">
                <i class="fa-solid fa-tag text-[7px]"></i>
                @if($hasDiscount)
                    Hemat {{ $diskon }}%
                @else
                    SALE
                @endif
            </div>
        @endif

        <div
            class="relative aspect-[3/4] rounded-[14px] md:rounded-[18px] overflow-hidden mb-2.5 md:mb-3 bg-gray-50 border border-gray-100">
            <img src="{{ $primaryImage ? asset('storage/' . $primaryImage->image_url) : 'https://via.placeholder.com/400x533?text=Al-Hayya' }}"
                class="product-image w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
                alt="{{ $product->name }}">

            <div
                class="absolute inset-0 bg-brand-dark/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                <div
                    class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-brand-dark text-sm shadow-xl scale-75 group-hover:scale-100 transition-transform">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>

        <div class="flex flex-col flex-grow px-0.5">
            <div class="flex justify-between items-center mb-1 gap-1">
                <p class="text-[8px] md:text-[10px] text-brand-primary font-bold uppercase tracking-wider truncate">
                    {{ $product->category->name }}
                </p>
                <div class="flex items-center gap-0.5 text-yellow-400 text-[9px] flex-shrink-0">
                    <i class="fa-solid fa-star"></i>
                    <span class="text-gray-400 font-medium">4.8</span>
                </div>
            </div>

            <h3
                class="font-semibold text-brand-dark text-[10px] md:text-xs line-clamp-2 mb-2 h-7 md:h-8 leading-snug group-hover:text-brand-primary transition-colors">
                {{ $product->name }}
            </h3>

            <div class="mt-auto pt-1.5 flex items-center justify-between gap-1 border-t border-gray-50">
                <div class="space-y-0">
                    <p class="text-xs md:text-sm font-extrabold text-brand-dark whitespace-nowrap">
                        Rp{{ number_format($displayPrice, 0, ',', '.') }}
                    </p>
                    @if($hasDiscount)
                        <p class="text-[8px] md:text-[10px] text-gray-400 line-through">
                            Rp{{ number_format($displayComparePrice, 0, ',', '.') }}
                        </p>
                    @endif
                </div>

                <div
                    class="w-7 h-7 md:w-8 md:h-8 bg-soft-mint text-brand-primary rounded-lg flex items-center justify-center shadow-inner hover:bg-brand-primary hover:text-white transition-all active:scale-90 flex-shrink-0 group-hover:rotate-[360deg] duration-500">
                    <i class="fa-solid fa-bag-shopping text-[10px]"></i>
                </div>
            </div>
        </div>
    </div>
</a>