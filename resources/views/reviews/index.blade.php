@extends('layouts.app')

@section('title', 'Ulasan Produk')

@section('content')
    <div class="mx-auto">
        <div class="mb-8">
            <h1 class="text-xl md:text-2xl font-extrabold text-brand-dark tracking-tight">Ulasan Pelanggan</h1>
            <nav class="text-xs md:text-sm text-gray-400 font-medium mt-1">
                <ol class="flex items-center gap-2">
                    <li><a href="/home" class="hover:text-brand-primary transition-colors">Dashboard</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li class="text-brand-dark">Ulasan</li>
                </ol>
            </nav>
        </div>

        <div class="bg-white rounded-[32px] shadow-sm border border-gray-50 overflow-hidden px-6 py-8">
            <table id="reviewTable" class="w-full text-sm">
                <thead>
                    <tr class="text-gray-400 text-[11px] uppercase tracking-widest border-b border-gray-50">
                        <th class="px-4 py-4 text-left">Produk</th>
                        <th class="px-4 py-4 text-left">Pelanggan</th>
                        <th class="px-4 py-4 text-left">Rating & Komentar</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($reviews as $review)
                        <tr class="hover:bg-soft-bg/50 transition-colors">
                            <td class="px-4 py-5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/' . $review->product->image) }}"
                                        class="w-10 h-10 rounded-xl object-cover border border-gray-100">
                                    <span
                                        class="font-bold text-brand-dark text-xs limit-text-1">{{ $review->product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark">{{ $review->user->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex flex-col gap-1">
                                    <div class="flex text-amber-400 text-[10px]">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="text-gray-600 text-xs italic">"{{ Str::limit($review->comment, 50) }}"</p>
                                    @if($review->images)
                                        <div class="flex gap-1 mt-1">
                                            @foreach(array_slice($review->images, 0, 3) as $img)
                                                <img src="{{ asset('storage/' . $img) }}"
                                                    class="w-6 h-6 rounded-md object-cover border border-gray-100">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider {{ $review->is_verified ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $review->is_verified ? 'Terverifikasi' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='viewReview(@json($review->load(["user", "product"])))'
                                        class="w-8 h-8 flex items-center justify-center bg-brand-primary/10 text-brand-primary rounded-xl hover:bg-brand-primary hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-eye text-[10px]"></i>
                                    </button>
                                    <form action="/reviews/{{ $review->id }}/toggle-verify" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center {{ $review->is_verified ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-600' }} rounded-xl hover:opacity-70 transition-all shadow-sm">
                                            <i
                                                class="fa-solid {{ $review->is_verified ? 'fa-xmark' : 'fa-check' }} text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="reviewModal"
        class="fixed inset-0 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
        <div
            class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden transform transition-all border border-white/20">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-11 h-11 rounded-2xl bg-brand-primary flex items-center justify-center text-white shadow-lg shadow-brand-primary/20">
                        <i class="fa-solid fa-message text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-brand-dark leading-tight">Detail Ulasan</h2>
                        <p id="modalUser" class="text-[10px] text-gray-400 font-bold uppercase tracking-widest"></p>
                    </div>
                </div>
                <button onclick="closeReviewModal()"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-gray-400 hover:text-red-500 transition-all">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-8">
                <div class="mb-6 flex items-center gap-4 p-4 rounded-2xl bg-gray-50/50 border border-gray-100">
                    <img id="prodImage" src="" class="w-14 h-14 rounded-xl object-cover shadow-sm">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Produk</p>
                        <h4 id="prodName" class="font-bold text-brand-dark text-sm"></h4>
                        <div id="starRating" class="flex text-amber-400 text-[10px] mt-1"></div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 block">Komentar</label>
                        <p id="revComment" class="text-sm text-gray-600 leading-relaxed italic"></p>
                    </div>

                    <div id="revImagesContainer" class="hidden">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 block">Foto
                            Pembeli</label>
                        <div id="revImages" class="flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-6 bg-gray-50/30 flex justify-end">
                <button onclick="closeReviewModal()"
                    class="px-10 py-3 rounded-2xl bg-white border border-gray-200 text-xs font-black uppercase text-gray-400 hover:bg-gray-50 transition-all">Tutup</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#reviewTable').DataTable({ responsive: true });
            });

            function viewReview(data) {
                $('#modalUser').text(data.user.name);
                $('#prodName').text(data.product.name);
                $('#prodImage').attr('src', '/storage/' + data.product.image);
                $('#revComment').text('"' + data.comment + '"');

                // Stars
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars += `<i class="fa-${i <= data.rating ? 'solid' : 'regular'} fa-star mr-0.5"></i>`;
                }
                $('#starRating').html(stars);

                // Images
                $('#revImages').empty();
                if (data.images && data.images.length > 0) {
                    $('#revImagesContainer').removeClass('hidden');
                    data.images.forEach(img => {
                        $('#revImages').append(`<img src="/storage/${img}" class="w-20 h-20 rounded-xl object-cover border border-gray-100 shadow-sm">`);
                    });
                } else {
                    $('#revImagesContainer').addClass('hidden');
                }

                $('#reviewModal').removeClass('hidden').addClass('flex');
            }

            function closeReviewModal() {
                $('#reviewModal').addClass('hidden').removeClass('flex');
            }
        </script>
    @endpush
@endsection