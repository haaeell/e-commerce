<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Login - VIBE WEAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .bg-pattern {
            background-color: #f8f9fa;
            background-image: radial-gradient(#e5e7eb 0.5px, transparent 0.5px);
            background-size: 20px 20px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-pattern p-6">

    <div class="w-full max-w-[1000px] flex flex-col md:flex-row bg-white rounded-[32px] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-100">
        
        <div class="w-full md:w-1/2 relative bg-black p-12 flex flex-col justify-between overflow-hidden">
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-zinc-800 rounded-full opacity-50 blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-zinc-700 rounded-full opacity-30 blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-shirt text-black text-xl"></i>
                    </div>
                    <span class="text-white font-bold text-2xl tracking-tighter uppercase">Vibe Wear</span>
                </div>
                
                <h2 class="text-4xl font-light text-white leading-tight">
                    Manage your <br>
                    <span class="font-bold">Apparel Empire.</span>
                </h2>
            </div>

            <div class="relative z-10">
                <p class="text-zinc-400 text-sm max-w-xs mb-6">
                    Panel administrasi khusus untuk manajemen stok kaos, pesanan pelanggan, dan analisis tren penjualan.
                </p>
                <div class="flex items-center gap-4 text-white/60 text-xs">
                    <span>v2.4.0</span>
                    <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                    <span>Secure Encryption</span>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center bg-white">
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-zinc-900 mb-2">Selamat Datang</h1>
                <p class="text-zinc-500">Masukkan kredensial Anda untuk masuk ke dashboard.</p>
            </div>

            <form id="loginForm" class="space-y-6">
                <div class="group">
                    <label class="block text-sm font-semibold text-zinc-700 mb-2 transition-colors group-focus-within:text-black">
                        Email Office
                    </label>
                    <div class="relative">
                        <input type="email" required
                            class="w-full px-0 py-3 bg-transparent border-b-2 border-zinc-200 focus:border-black transition-all outline-none text-zinc-900 placeholder:text-zinc-300"
                            placeholder="admin@vibewear.com">
                    </div>
                </div>

                <div class="group">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-semibold text-zinc-700 transition-colors group-focus-within:text-black">
                            Password
                        </label>
                        <a href="#" class="text-xs font-medium text-zinc-400 hover:text-black transition">Lupa password?</a>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" required
                            class="w-full px-0 py-3 bg-transparent border-b-2 border-zinc-200 focus:border-black transition-all outline-none text-zinc-900 placeholder:text-zinc-300"
                            placeholder="••••••••">
                        
                        <button type="button" id="togglePassword" class="absolute right-0 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-black">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-3 py-2">
                    <input type="checkbox" id="remember" class="w-4 h-4 rounded border-zinc-300 text-black focus:ring-black cursor-pointer">
                    <label for="remember" class="text-sm text-zinc-600 cursor-pointer select-none">Ingat perangkat ini</label>
                </div>

                <button type="submit" id="loginBtn"
                    class="w-full py-4 bg-black hover:bg-zinc-800 text-white font-semibold rounded-2xl transition-all active:scale-[0.99] flex items-center justify-center gap-3 shadow-lg shadow-zinc-200">
                    <span id="btnText">Masuk Sekarang</span>
                    <i id="btnIcon" class="fa-solid fa-arrow-right text-sm"></i>
                    
                    <svg id="btnLoader" class="hidden w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-12 pt-8 border-t border-zinc-100">
                <p class="text-center text-zinc-400 text-xs">
                    &copy; 2026 <strong>VIBE WEAR</strong>. All rights reserved. <br>
                    Internal Admin System.
                </p>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById("loginForm");
        const btn = document.getElementById("loginBtn");
        const btnText = document.getElementById("btnText");
        const btnIcon = document.getElementById("btnIcon");
        const btnLoader = document.getElementById("btnLoader");
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        // Toggle Password Visibility
        togglePassword.addEventListener("click", () => {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            togglePassword.innerHTML = isPassword ? '<i class="fa-regular fa-eye-slash"></i>' : '<i class="fa-regular fa-eye"></i>';
        });

        // Form Submission Simulation
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            
            // UI Feedback
            btn.disabled = true;
            btnText.textContent = "Memverifikasi...";
            btnIcon.classList.add("hidden");
            btnLoader.classList.remove("hidden");
            btn.classList.replace("bg-black", "bg-zinc-700");

            // Simulasi Delay
            setTimeout(() => {
                alert("Berhasil masuk ke Dashboard Vibe Wear!");
                
                // Reset UI
                btn.disabled = false;
                btnText.textContent = "Masuk Sekarang";
                btnIcon.classList.remove("hidden");
                btnLoader.classList.add("hidden");
                btn.classList.replace("bg-zinc-700", "bg-black");
            }, 2000);
        });
    </script>

</body>

</html>