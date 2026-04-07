<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AL-HAYYA HIJAB - @yield('title', 'Elegansi dalam Kesantunan')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'soft-mint': '#F1F8E9',
                        'soft-blue': '#E3F2FD',
                        'brand-primary': '#81C784',
                        'brand-secondary': '#A5D6A7',
                        'brand-dark': '#2D5A27',
                    },
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                }
            }
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .product-card:hover .product-image {
            transform: scale(1.08);
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .animate-shimmer {
            animation: shimmer 2s infinite;
        }

        .select2-container--default .select2-selection--single {
            border-radius: 0.75rem;
            border-color: #f3f4f6;
            height: 46px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
        }

        #datatable_wrapper {
            font-family: 'Poppins', sans-serif;
            margin-top: 1rem;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        /* Search Input - Simple */
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e5e7eb !important;
            border-radius: 1rem !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            height: 44px !important;
            background: #f9fafb !important;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #81C784 !important;
            box-shadow: 0 0 0 3px rgba(129, 199, 132, 0.1) !important;
            outline: none !important;
        }

        /* Length Menu */
        .dataTables_wrapper .dataTables_length select {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            height: 44px;
        }

        /* Pagination - Simple */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.75rem !important;
            margin: 0 0.125rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            border: 1px solid #e5e7eb !important;
            background: white !important;
            color: #6b7280 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: #81C784 !important;
            border-color: #81C784 !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #81C784 !important;
            border-color: #81C784 !important;
            color: white !important;
        }

        /* Table Header */
        #datatable thead th {
            background: #f8fafc !important;
            color: #6b7280 !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 1.25rem 1rem !important;
            border: none !important;
        }

        /* Sorting Icons */
        table.dataTable thead .sorting::after,
        table.dataTable thead .sorting_asc::after,
        table.dataTable thead .sorting_desc::after {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900;
            opacity: 0.4;
            margin-left: 0.5rem;
        }

        table.dataTable thead .sorting::after {
            content: "\f0dc";
        }

        table.dataTable thead .sorting_asc::after {
            content: "\f0de";
            color: #81C784;
        }

        table.dataTable thead .sorting_desc::after {
            content: "\f0dd";
            color: #81C784;
        }

        /* Rows */
        #datatable tbody tr:hover {
            background: rgba(129, 199, 132, 0.05) !important;
        }

        /* Info */
        .dataTables_wrapper .dataTables_info {
            color: #9ca3af;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                margin-bottom: 1rem;
            }
        }
    </style>
    @yield('styles')
</head>

<body class="bg-white font-sans text-gray-900 antialiased overflow-x-hidden">

    <nav class="fixed w-full z-50 glass-nav border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2.5 group">
                <div
                    class="w-10 h-10 bg-brand-primary rounded-xl flex items-center justify-center shadow-lg shadow-brand-primary/20 group-hover:rotate-12 transition-transform">
                    <i class="fa-solid fa-wand-magic-sparkles text-white text-lg"></i>
                </div>
                <span class="text-brand-dark font-extrabold text-xl tracking-tight uppercase">Al-Hayya</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-brand-dark/80">
                <a href="/"
                    class="hover:text-brand-primary transition-colors relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-0.5 after:bg-brand-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform">Beranda</a>
                <a href="{{ route('collections.index') }}"
                    class="hover:text-brand-primary transition-colors">Koleksi</a>
                <a href="#" class="hover:text-brand-primary transition-colors">Promo</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/cart" class="relative p-2 text-brand-dark hover:text-brand-primary transition-colors">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span
                        class="absolute top-0 right-0 bg-brand-primary text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center border-2 border-white shadow">
                        @auth
                                            {{ \App\Models\CartItem::whereHas('cart', function ($q) {
                            $q->where('user_id', auth()->id()); })->count() }}
                        @else
                            0
                        @endauth
                    </span>
                </a>

                @auth
                    <div class="relative ml-2" id="userDropdownContainer">
                        <button type="button" id="userDropdownBtn"
                            class="flex items-center gap-3 pl-3 pr-1 py-1 bg-gray-50 border border-gray-100 rounded-2xl hover:bg-white hover:shadow-md transition-all duration-300">
                            <span class="hidden md:block text-sm font-bold text-brand-dark">{{ Auth::user()->name }}</span>
                            <div
                                class="w-9 h-9 bg-brand-primary/10 rounded-xl flex items-center justify-center border border-brand-primary/20">
                                <i class="fa-solid fa-user text-brand-primary text-sm"></i>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 mr-2 transition-transform duration-300"
                                id="dropdownArrow"></i>
                        </button>

                        <div id="userDropdownMenu"
                            class="hidden absolute right-0 mt-3 w-56 bg-white/90 backdrop-blur-xl rounded-[24px] shadow-[0_20px_50px_rgba(45,90,39,0.1)] border border-white p-2 z-[60] origin-top-right transition-all">

                            <div class="px-4 py-3 border-b border-gray-50 mb-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Role Akun</p>
                                <p class="text-xs font-bold text-brand-primary uppercase">{{ Auth::user()->role }}</p>
                            </div>

                            <a href="/profile"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-brand-dark hover:bg-soft-mint rounded-2xl transition-colors group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center group-hover:bg-white transition-colors">
                                    <i class="fa-solid fa-gear text-xs text-gray-400 group-hover:text-brand-primary"></i>
                                </div>
                                Pengaturan Akun
                            </a>

                            <a href="/order-history"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-brand-dark hover:bg-soft-mint rounded-2xl transition-colors group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center group-hover:bg-white transition-colors">
                                    <i
                                        class="fa-solid fa-clock-rotate-left text-xs text-gray-400 group-hover:text-brand-primary"></i>
                                </div>
                                Riwayat Pesanan
                            </a>

                            <hr class="my-2 border-gray-50">

                            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 rounded-2xl transition-colors group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-red-50/50 flex items-center justify-center group-hover:bg-white transition-colors">
                                    <i class="fa-solid fa-power-off text-xs group-hover:scale-110 transition-transform"></i>
                                </div>
                                Keluar
                            </button>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login"
                        class="hidden md:block text-sm font-semibold text-brand-dark hover:text-brand-primary transition-colors">Masuk</a>
                    <a href="/register"
                        class="px-6 py-3 bg-brand-primary text-white text-sm font-bold rounded-xl shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/40 hover:-translate-y-0.5 transition-all active:scale-95">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-20">
        @yield('content')
    </main>

    <footer class="bg-brand-dark text-white pt-20 pb-10 mt-16 rounded-t-[50px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-brand-secondary text-xl"></i>
                    <span class="text-white font-extrabold text-xl tracking-tight uppercase">Al-Hayya</span>
                </div>
                <p class="text-brand-secondary/70 text-sm leading-relaxed">Elegansi dalam Kesantunan. Temukan koleksi
                    hijab premium terbaik bersama kami.</p>
                <div class="flex gap-4 pt-4">
                    <a href="#"
                        class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-brand-primary transition-colors"><i
                            class="fa-brands fa-instagram"></i></a>
                    <a href="#"
                        class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-brand-primary transition-colors"><i
                            class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
            <div class="col-span-3 text-right text-brand-secondary/50 text-xs self-end">
                &copy; 2026 AL-HAYYA HIJAB. All rights reserved.
            </div>
        </div>
    </footer>

    <div class="md:hidden fixed bottom-6 left-6 right-6 z-50">
        <div
            class="bg-brand-dark/95 backdrop-blur-lg rounded-[28px] shadow-2xl px-8 py-5 flex justify-between items-center border border-white/10">
            <a href="/" class="text-brand-primary flex flex-col items-center">
                <i class="fa-solid fa-house text-xl"></i>
            </a>
            <a href="#" class="text-white/50 flex flex-col items-center">
                <i class="fa-solid fa-magnifying-glass text-xl"></i>
            </a>
            <a href="#" class="text-white/50 flex flex-col items-center">
                <i class="fa-solid fa-heart text-xl"></i>
            </a>
            <a href="/login" class="text-white/50 flex flex-col items-center">
                <i class="fa-solid fa-user text-xl"></i>
            </a>
        </div>
    </div>

    <div class="h-28 md:hidden"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')

    <script>
        $(document).ready(function () {
            const btn = $('#userDropdownBtn');
            const menu = $('#userDropdownMenu');
            const arrow = $('#dropdownArrow');

            btn.on('click', function (e) {
                e.stopPropagation();
                menu.toggleClass('hidden');
                arrow.toggleClass('rotate-180');
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('#userDropdownContainer').length) {
                    menu.addClass('hidden');
                    arrow.removeClass('rotate-180');
                }
            });
        });
    </script>
</body>

</html>