<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Yönetim Paneli Girişi | Yusuf Akboğa Ayakkabı</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (Play CDN with Config) -->
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
                        },
                        canvas: '#F6F8FC',
                        surface: '#FFFFFF',
                        border: '#E5E7EB',
                        textPrimary: '#111827',
                        textSecondary: '#6B7280',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        html, body {
            height: 100%;
            overflow-x: hidden;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F6F8FC;
            color: #111827;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        /* Mobile Input Zoom Fix */
        @media screen and (max-width: 640px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }
        /* Gentle Entrance Keyframe */
        @keyframes loginFadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-up {
            animation: loginFadeUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="h-full flex flex-col selection:bg-blue-600 selection:text-white">

    <div class="flex-grow flex w-full min-h-screen">
        
        <!-- LEFT COLUMN: Premium Hero Visual (58% Desktop Width) -->
        <div class="hidden lg:flex lg:w-[58%] xl:w-[60%] relative bg-slate-950 flex-col justify-between p-10 xl:p-14 overflow-hidden select-none">
            
            <!-- High-Res Sneaker Image Background -->
            <img src="https://images.unsplash.com/photo-1552346154-21d32810aba3?w=1600&auto=format&fit=crop&q=85" 
                 alt="Yusuf Akboğa Sneaker" 
                 class="absolute inset-0 w-full h-full object-cover object-center scale-105 transition-transform duration-1000 ease-out"
                 loading="eager">

            <!-- Dark Elegant Gradient Overlays -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F19] via-[#0B0F19]/75 to-[#0B0F19]/40"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0B0F19]/80 via-transparent to-transparent"></div>

            <!-- Top Brand Tag -->
            <div class="relative z-10 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600/90 text-white flex items-center justify-center font-extrabold text-sm shadow-lg shadow-blue-500/20 backdrop-blur-xs border border-white/10">
                    YA
                </div>
                <div>
                    <span class="block font-bold text-white text-base tracking-tight leading-tight">YUSUF AKBOĞA</span>
                    <span class="block text-[10px] tracking-[0.25em] font-semibold text-blue-400 uppercase">YÖNETİM SİSTEMİ</span>
                </div>
            </div>

            <!-- Middle / Bottom Hero Content -->
            <div class="relative z-10 max-w-xl mb-4">
                
                <!-- Badge -->
                <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-white text-xs font-medium mb-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Yönetici Paneli v2.0</span>
                </div>

                <!-- Main Titles -->
                <h1 class="font-extrabold text-3xl xl:text-4xl text-white tracking-tight leading-tight mb-2">
                    YUSUF AKBOĞA
                </h1>
                <p class="text-sm font-semibold tracking-[0.2em] text-blue-400 uppercase mb-4">
                    AYAKKABI / FOOTWEAR
                </p>

                <p class="text-base text-gray-300 font-normal leading-relaxed mb-8">
                    “Mağazanı yönet, siparişlerini takip et, stoklarını kontrol altında tut.”
                </p>

                <!-- Premium Feature Pills -->
                <div class="flex flex-wrap gap-2.5">
                    <div class="flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white/10 backdrop-blur-md border border-white/10 text-white text-xs font-semibold">
                        <i class="fa-solid fa-bag-shopping text-blue-400 text-xs"></i>
                        <span>Sipariş Yönetimi</span>
                    </div>
                    <div class="flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white/10 backdrop-blur-md border border-white/10 text-white text-xs font-semibold">
                        <i class="fa-solid fa-boxes-stacked text-blue-400 text-xs"></i>
                        <span>Stok Takibi</span>
                    </div>
                    <div class="flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white/10 backdrop-blur-md border border-white/10 text-white text-xs font-semibold">
                        <i class="fa-solid fa-shoe-prints text-blue-400 text-xs"></i>
                        <span>Ürün Yönetimi</span>
                    </div>
                </div>

            </div>

            <!-- Hero Footer -->
            <div class="relative z-10 text-xs text-gray-400 font-medium">
                © {{ date('Y') }} Yusuf Akboğa Ayakkabı. Tüm hakları saklıdır.
            </div>

        </div>

        <!-- RIGHT COLUMN: Login Form Area (42% Desktop Width) -->
        <div class="w-full lg:w-[42%] xl:w-[40%] flex flex-col justify-between p-5 sm:p-8 lg:p-10 xl:p-12 bg-[#F6F8FC] min-h-screen">
            
            <!-- Top Bar / Back to Store -->
            <div class="flex items-center justify-between w-full max-w-md mx-auto mb-6">
                <!-- Mobile Logo (Shown only on small screens) -->
                <div class="flex items-center space-x-2.5 lg:hidden">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                        YA
                    </div>
                    <span class="font-extrabold text-sm text-[#111827] tracking-tight">YUSUF AKBOĞA</span>
                </div>

                <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-[#6B7280] hover:text-[#2563EB] transition-colors ml-auto py-1.5 px-3 rounded-lg hover:bg-white border border-transparent hover:border-[#E5E7EB]">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Mağazaya Geri Dön</span>
                </a>
            </div>

            <!-- Centered Form Container -->
            <div class="w-full max-w-md mx-auto my-auto animate-fade-up">
                
                <!-- White SaaS Card -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 sm:p-9 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
                    
                    <!-- Form Header -->
                    <div class="mb-7">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg mb-3.5 border border-blue-100/60">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <h2 class="font-bold text-2xl text-[#111827] tracking-tight">
                            Yusuf Akboğa
                        </h2>
                        <p class="text-sm font-medium text-[#6B7280] mt-1">
                            Yönetim Paneline Giriş
                        </p>
                    </div>

                    <!-- Flash Message: Error -->
                    @if(session('error'))
                        <div class="mb-5 bg-[#FEF2F2] border border-[#FECACA] text-[#B91C1C] px-4 py-3 rounded-xl text-xs flex items-center space-x-2.5 animate-fade-up">
                            <i class="fa-solid fa-circle-exclamation text-[#DC2626] text-sm flex-shrink-0"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Flash Message: Success -->
                    @if(session('success'))
                        <div class="mb-5 bg-[#F0FDF4] border border-[#BBF7D0] text-[#15803D] px-4 py-3 rounded-xl text-xs flex items-center space-x-2.5 animate-fade-up">
                            <i class="fa-solid fa-circle-check text-[#16A34A] text-sm flex-shrink-0"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form id="adminLoginForm" method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                        @csrf

                        <!-- E-Mail Input -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-[#374151] uppercase tracking-wider mb-1.5">
                                E-Posta Adresi <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#9CA3AF]">
                                    <i class="fa-regular fa-envelope text-sm"></i>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email" 
                                       autofocus 
                                       placeholder="admin@yusufakboga.com"
                                       class="w-full h-[50px] bg-white border @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/10 @else border-[#E5E7EB] focus:border-[#2563EB] focus:ring-blue-500/15 @enderror rounded-xl pl-10 pr-4 text-[15px] text-[#111827] placeholder-[#9CA3AF] focus:outline-none focus:ring-4 transition-all">
                            </div>
                            @error('email')
                                <p class="text-xs text-[#DC2626] mt-1.5 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Input with Show/Hide -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-[#374151] uppercase tracking-wider mb-1.5">
                                Yönetici Şifresi <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#9CA3AF]">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </div>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       required 
                                       autocomplete="current-password" 
                                       placeholder="••••••••"
                                       class="w-full h-[50px] bg-white border @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/10 @else border-[#E5E7EB] focus:border-[#2563EB] focus:ring-blue-500/15 @enderror rounded-xl pl-10 pr-11 text-[15px] text-[#111827] placeholder-[#9CA3AF] focus:outline-none focus:ring-4 transition-all">
                                
                                <button type="button" 
                                        onclick="togglePasswordVisibility()" 
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#9CA3AF] hover:text-[#111827] transition-colors focus:outline-none" 
                                        title="Şifreyi Göster / Gizle" 
                                        aria-label="Şifreyi Göster veya Gizle">
                                    <i id="toggleEyeIcon" class="fa-regular fa-eye text-sm"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-[#DC2626] mt-1.5 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center space-x-2.5 cursor-pointer select-none">
                                <input type="checkbox" 
                                       name="remember" 
                                       id="remember" 
                                       class="w-4 h-4 rounded border-[#D1D5DB] text-[#2563EB] focus:ring-[#2563EB] focus:ring-offset-0 transition-colors">
                                <span class="text-xs font-semibold text-[#4B5563]">Beni Hatırla</span>
                            </label>
                        </div>

                        <!-- Submit Button with Loading State -->
                        <div class="pt-2">
                            <button type="submit" 
                                    id="submitBtn" 
                                    class="w-full h-[50px] bg-[#2563EB] hover:bg-[#1D4ED8] active:bg-[#1E40AF] text-white font-semibold text-[15px] rounded-xl shadow-sm hover:shadow-md hover:shadow-blue-600/20 transition-all duration-200 flex items-center justify-center space-x-2 disabled:opacity-75 disabled:cursor-not-allowed">
                                <span id="btnIcon"><i class="fa-solid fa-right-to-bracket text-sm"></i></span>
                                <span id="btnText">Panele Giriş Yap</span>
                            </button>
                        </div>
                    </form>

                    <!-- Card Security Footer -->
                    <div class="mt-6 pt-5 border-t border-[#E5E7EB] flex items-center justify-center gap-1.5 text-xs text-[#6B7280] font-medium">
                        <i class="fa-solid fa-shield-halved text-[#2563EB] text-xs"></i>
                        <span>256-bit SSL korumalı güvenli yönetici girişi</span>
                    </div>

                </div>

            </div>

            <!-- Right Column Bottom Copyright -->
            <div class="w-full max-w-md mx-auto text-center text-xs text-[#9CA3AF] mt-6">
                Yusuf Akboğa Ayakkabı E-Ticaret Yönetim Platformu
            </div>

        </div>

    </div>

    <!-- Interactive Client Script -->
    <script>
        // Password Visibility Toggle
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('toggleEyeIcon');
            
            if (!passwordInput || !eyeIcon) return;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Form Submit Loading State & Double-Click Prevention
        const loginForm = document.getElementById('adminLoginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnIcon = document.getElementById('btnIcon');
        const btnText = document.getElementById('btnText');

        if (loginForm && submitBtn) {
            loginForm.addEventListener('submit', function(e) {
                // Check HTML5 validity
                if (!loginForm.checkValidity()) {
                    return;
                }

                // Apply loading state
                submitBtn.disabled = true;
                btnIcon.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-sm"></i>';
                btnText.textContent = 'Giriş Yapılıyor...';
            });
        }
    </script>
</body>
</html>
