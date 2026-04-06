<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Akun - AL-HAYYA HIJAB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'soft-mint': '#F1F8E9', 'brand-primary': '#81C784', 'brand-dark': '#2D5A27' }, fontFamily: { sans: ['Poppins', 'sans-serif'] } } }
        }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 p-4">
    <div class="w-full max-w-4xl bg-white rounded-[40px] overflow-hidden shadow-2xl flex flex-col md:flex-row">
        <div class="w-full md:w-1/3 bg-brand-dark p-10 text-white flex flex-col justify-center">
            <h2 class="text-3xl font-bold mb-4">Bergabung Bersama Kami</h2>
            <p class="text-white/70 text-sm leading-relaxed">Dapatkan kemudahan dalam berbelanja hijab premium dan update koleksi terbaru setiap harinya.</p>
        </div>

        <div class="w-full md:w-2/3 p-8 md:p-12">
            <div id="alertContainer"></div>
            <form id="ajaxRegisterForm" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:border-brand-primary outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Nomor WhatsApp</label>
                        <input type="text" name="phone" placeholder="0812..." required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:border-brand-primary outline-none transition-all">
                    </div>
                </div>
                
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Alamat Email</label>
                    <input type="email" name="email" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:border-brand-primary outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Password</label>
                        <input type="password" name="password" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:border-brand-primary outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:border-brand-primary outline-none transition-all">
                    </div>
                </div>

                <button type="submit" id="regBtn" class="w-full py-4 bg-brand-primary text-white font-bold rounded-2xl shadow-lg hover:shadow-brand-primary/30 transition-all mt-4 flex items-center justify-center gap-2">
                    <span id="regText">Daftar Sekarang</span>
                </button>
                
                <p class="text-center text-sm text-gray-500 mt-4">Sudah punya akun? <a href="/login" class="text-brand-primary font-bold">Login</a></p>
            </form>
        </div>
    </div>

    <script>
        $('#ajaxRegisterForm').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#regBtn');
            const text = $('#regText');

            $.ajax({
                url: "{{ route('register') }}",
                method: "POST",
                data: $(this).serialize(),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                beforeSend: function() {
                    btn.prop('disabled', true).addClass('opacity-70');
                    text.text('Mendaftarkan...');
                    $('#alertContainer').empty();
                },
                success: function(res) {
                    window.location.href = res.redirect;
                },
                error: function(xhr) {
                    btn.prop('disabled', false).removeClass('opacity-70');
                    text.text('Daftar Sekarang');
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<div class="mb-4 p-4 bg-red-50 text-red-600 rounded-2xl text-xs"><ul>';
                    $.each(errors, function(key, val) { errorHtml += `<li>${val[0]}</li>`; });
                    errorHtml += '</ul></div>';
                    $('#alertContainer').html(errorHtml);
                }
            });
        });
    </script>
</body>
</html>