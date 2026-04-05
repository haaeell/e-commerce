@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <div class="bg-white p-6 rounded-[32px] border border-white shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-soft-mint rounded-2xl flex items-center justify-center text-brand-primary text-2xl">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Total Sales</p>
                <h3 class="text-xl font-extrabold text-brand-dark">Rp 12.8M</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[32px] border border-white shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 text-2xl">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Pesanan</p>
                <h3 class="text-xl font-extrabold text-brand-dark">1,240</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[32px] border border-white shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 text-2xl">
                <i class="fa-solid fa-shirt"></i>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Produk</p>
                <h3 class="text-xl font-extrabold text-brand-dark">86 Hijab</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[32px] border border-white shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-500 text-2xl">
                <i class="fa-solid fa-star"></i>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Rating</p>
                <h3 class="text-xl font-extrabold text-brand-dark">4.9/5.0</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[40px] border border-white shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 flex flex-col sm:flex-row justify-between items-center gap-4 border-b border-gray-50">
            <h2 class="text-lg font-extrabold text-brand-dark">Transaksi Terbaru</h2>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input type="text" placeholder="Cari pesanan..."
                    class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm w-full outline-none focus:border-brand-primary">
                <button class="p-2.5 bg-brand-primary text-white rounded-xl shadow-lg shadow-brand-primary/20"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Customer</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="group hover:bg-soft-mint/30 transition-all">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-xl overflow-hidden shadow-sm">
                                    <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?q=80&w=200&auto=format&fit=crop"
                                        class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-brand-dark">Pashmina Silk Premium</p>
                                    <p class="text-[10px] text-gray-400 font-semibold">Dusty Rose - L</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-semibold text-gray-700">Anisa Rahma</p>
                            <p class="text-[10px] text-gray-400 uppercase">Jakarta Selatan</p>
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-brand-dark">Rp 155.000</td>
                        <td class="px-8 py-5">
                            <span
                                class="px-3 py-1 bg-yellow-100 text-yellow-600 text-[10px] font-bold uppercase rounded-lg">Dikemas</span>
                        </td>
                        <td class="px-8 py-5">
                            <button
                                class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-brand-primary hover:text-white transition-all"><i
                                    class="fa-solid fa-ellipsis"></i></button>
                        </td>
                    </tr>
                    <tr class="group hover:bg-soft-mint/30 transition-all">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-xl overflow-hidden shadow-sm">
                                    <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=200&auto=format&fit=crop"
                                        class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-brand-dark">Hijab Square Voal</p>
                                    <p class="text-[10px] text-gray-400 font-semibold">Olive Green</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-semibold text-gray-700">Fatimah Zahra</p>
                            <p class="text-[10px] text-gray-400 uppercase">Bandung</p>
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-brand-dark">Rp 89.000</td>
                        <td class="px-8 py-5">
                            <span
                                class="px-3 py-1 bg-green-100 text-green-600 text-[10px] font-bold uppercase rounded-lg">Selesai</span>
                        </td>
                        <td class="px-8 py-5">
                            <button
                                class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-brand-primary hover:text-white transition-all"><i
                                    class="fa-solid fa-ellipsis"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection