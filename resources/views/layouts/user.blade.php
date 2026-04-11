<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BP Transportation</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Navbar */
        .user-nav {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .nav-link {
            position: relative;
            padding: 0.6rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.025em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #d1d5db;
        }
        .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
            transform: translateY(-1px);
        }
        .nav-link.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(16,185,129,0.4);
        }

        /* Content area */
        .user-content {
            background: linear-gradient(180deg, #f0f4f8 0%, #e2e8f0 100%);
            min-height: calc(100vh - 4.5rem);
        }

        /* Cards */
        .user-card {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 4px 25px rgba(0,0,0,0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .user-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            font-weight: 700;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16,185,129,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16,185,129,0.45);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99,102,241,0.4);
        }

        /* Form inputs */
        .form-input {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            background: #f9fafb;
        }
        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
            background: #fff;
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            margin-bottom: 0.375rem;
            display: block;
        }

        /* Status badge */
        .badge {
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-accepted { background: #d1fae5; color: #065f46; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-delivered { background: #dbeafe; color: #1e40af; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .animate-delay-3 { animation-delay: 0.3s; opacity: 0; }

        /* Mobile nav toggle */
        .mobile-menu { display: none; }
        .mobile-menu.open { display: flex; }
    </style>
</head>
<body class="antialiased">

<!-- Navigation Bar -->
<nav class="user-nav sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-[4.5rem]">
            <!-- Logo -->
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <img src="{{ asset('images/colt.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                </div>
                <span class="text-white font-bold text-xl tracking-tight">BP Transportation</span>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('user.dashboard') }}"
                   class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('user.pesanan') }}"
                   class="nav-link {{ request()->routeIs('user.pesanan') || request()->routeIs('user.pesanan.detail') ? 'active' : '' }}">
                    Pesanan
                </a>
                <a href="{{ route('user.profile') }}"
                   class="nav-link {{ request()->routeIs('user.profile') || request()->routeIs('user.profile.edit') ? 'active' : '' }}">
                    Profile
                </a>
            </div>

            <!-- Mobile hamburger -->
            <button onclick="document.getElementById('mobileMenu').classList.toggle('open')"
                    class="md:hidden text-white p-2 rounded-lg hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Nav -->
        <div id="mobileMenu" class="mobile-menu flex-col gap-2 pb-4 md:hidden">
            <a href="{{ route('user.dashboard') }}"
               class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('user.pesanan') }}"
               class="nav-link {{ request()->routeIs('user.pesanan') || request()->routeIs('user.pesanan.detail') ? 'active' : '' }}">
                Pesanan
            </a>
            <a href="{{ route('user.profile') }}"
               class="nav-link {{ request()->routeIs('user.profile') || request()->routeIs('user.profile.edit') ? 'active' : '' }}">
                Profile
            </a>
        </div>
    </div>
</nav>

<!-- Page Content -->
<main class="user-content">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 animate-fade-in-up">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3 animate-fade-in-up">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot }}
    </div>
</main>

@livewireScripts
</body>
</html>
