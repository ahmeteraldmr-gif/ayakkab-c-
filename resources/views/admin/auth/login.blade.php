<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi | Yusuf Akboğa</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#2563EB',
                            hover: '#1D4ED8',
                            light: '#EFF6FF',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F6F8FC] min-h-screen flex items-center justify-center p-4 selection:bg-blue-600 selection:text-white font-sans text-slate-800 antialiased">

    <div class="w-full max-w-md">
        
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-xl shadow-md shadow-blue-500/20 mb-3">
                <i class="fa-solid fa-shoe-prints text-lg"></i>
            </div>
            <h1 class="font-display font-bold text-2xl text-slate-900 tracking-tight">Yusuf Akboğa</h1>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Yönetim Paneli Girişi</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-8 sm:p-9 shadow-xl shadow-slate-200/50 relative">

            @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">E-Posta Adresi</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email', 'admin@yusufakboga.com') }}" required 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">
                        <i class="fa-solid fa-envelope absolute right-3.5 top-3 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Yönetici Şifresi</label>
                    <div class="relative">
                        <input type="password" name="password" value="admin123" required 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">
                        <i class="fa-solid fa-lock absolute right-3.5 top-3 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-[#D1D5DB] text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span>Beni Hatırla</span>
                    </label>
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-medium text-[11px]">Demo: admin123</span>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg transition-all shadow-md shadow-blue-600/20 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        <span>Panele Giriş Yap</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-[#E5E7EB] text-center">
                <a href="{{ route('home') }}" class="text-xs font-medium text-slate-500 hover:text-blue-600 transition-colors inline-flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Mağazaya Geri Dön
                </a>
            </div>

        </div>

    </div>

</body>
</html>
