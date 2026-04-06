<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Admin Login - AL-HAYYA HIJAB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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
        .bg-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#81C784 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .animate-shimmer {
            animation: shimmer 2s infinite;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-pattern p-4 sm:p-6">

    <div
        class="w-full max-w-5xl flex flex-col md:flex-row bg-white/80 rounded-[40px] overflow-hidden shadow-[0_32px_64px_-16px_rgba(45,90,39,0.1)] border border-white backdrop-blur-xl">

        <div
            class="w-full md:w-[42%] relative bg-gradient-to-br from-soft-mint via-white to-soft-blue p-10 md:p-14 flex flex-col justify-between overflow-hidden">
            <div
                class="absolute top-[-10%] right-[-10%] w-64 h-64 bg-brand-primary/10 rounded-full blur-3xl text-center">
            </div>

            <div class="relative z-10 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-4 mb-12">
                    <div
                        class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-brand-primary/10">
                        <i class="fa-solid fa-wand-magic-sparkles text-brand-primary text-2xl"></i>
                    </div>
                    <span class="text-brand-dark font-extrabold text-2xl tracking-tight uppercase">Al-Hayya</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-brand-dark leading-[1.1] tracking-tight">Purely
                    <br><span class="text-brand-primary italic font-medium">Elegant.</span>
                </h2>
            </div>
        </div>

        <div class="w-full md:w-[58%] p-8 sm:p-12 md:p-20 flex flex-col justify-center bg-white">
            <div id="alertContainer"></div>

            <form id="ajaxLoginForm" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[13px] font-bold text-brand-dark/70 ml-1">EMAIL OFFICE</label>
                    <input type="email" name="email" required
                        class="w-full px-5 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none"
                        placeholder="admin@alhayya.com">
                </div>

                <div class="space-y-2">
                    <label class="text-[13px] font-bold text-brand-dark/70 ml-1 uppercase">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-5 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none"
                            placeholder="••••••••">
                        <button type="button" id="togglePassword"
                            class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-brand-primary"><i
                                class="fa-regular fa-eye"></i></button>
                    </div>
                </div>

                <button type="submit" id="loginBtn"
                    class="relative w-full py-4.5 bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold rounded-2xl transition-all duration-300 hover:shadow-[0_20px_40px_-10px_rgba(129,199,132,0.4)] hover:-translate-y-1 active:scale-[0.97] flex items-center justify-center gap-3 overflow-hidden group mt-4">
                    <div
                        class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer">
                    </div>
                    <span id="btnText" class="relative z-10">Masuk ke Dashboard</span>
                    <div id="btnIcon"
                        class="relative z-10 w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center group-hover:rotate-12 transition-transform">
                        <i class="fa-solid fa-sparkles text-[10px]"></i>
                    </div>
                    <svg id="btnLoader" class="hidden w-5 h-5 animate-spin text-white relative z-10"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function () {
            $('#togglePassword').click(function () {
                const input = $('#password');
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            $('#ajaxLoginForm').on('submit', function (e) {
                e.preventDefault();
                const btn = $('#loginBtn');
                const loader = $('#btnLoader');
                const icon = $('#btnIcon');
                const text = $('#btnText');

                $.ajax({
                    url: "{{ route('login') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    beforeSend: function () {
                        btn.prop('disabled', true).addClass('opacity-80');
                        loader.removeClass('hidden');
                        icon.addClass('hidden');
                        text.text('Memproses...');
                        $('#alertContainer').empty();
                    },
                    success: function (response) {
                        window.location.href = response.redirect;
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).removeClass('opacity-80');
                        loader.addClass('hidden');
                        icon.removeClass('hidden');
                        text.text('Masuk ke Dashboard');

                        let errorMsg = xhr.responseJSON?.message || "Terjadi kesalahan.";
                        $('#alertContainer').html(`
                            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-800 text-sm font-medium">
                                <i class="fas fa-exclamation-circle mr-2"></i> ${errorMsg}
                            </div>
                        `);
                    }
                });
            });
        });
    </script>
</body>

</html>