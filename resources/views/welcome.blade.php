<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Welcome - AttendanceApp</title>
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 min-h-screen">
        <div class="min-h-screen flex flex-col items-center justify-center px-4">
            {{-- debug banner removed --}}
            <!-- Navigation Bar -->
            <nav class="absolute top-0 right-0 p-6 z-10">
                @auth
                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 font-semibold shadow-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold shadow-lg transition-all">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 font-semibold shadow-lg transition-all">
                            Log in
                        </a>
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-indigo-700 text-white rounded-lg hover:bg-indigo-800 font-semibold shadow-lg transition-all">
                                Register
                            </a>
                        @endif
                    </div>
                @endauth
            </nav>

            <div class="text-center max-w-4xl mx-auto">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-2xl shadow-2xl mb-6 transform hover:scale-110 transition-transform">
                        <svg class="w-14 h-14 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-6xl font-bold text-white mb-4">Welcome to AttendanceApp</h1>
                    <p class="text-xl text-indigo-100 mb-8">Manage attendance, leaves, and tasks with ease</p>
                </div>

                <div class="grid md:grid-cols-3 gap-6 mt-12">
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-white border border-white/20">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Easy Attendance</h3>
                        <p class="text-sm text-indigo-100">Mark and track attendance effortlessly</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-white border border-white/20">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Leave Management</h3>
                        <p class="text-sm text-indigo-100">Request and manage leaves seamlessly</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-white border border-white/20">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Task Tracking</h3>
                        <p class="text-sm text-indigo-100">Submit and track your tasks</p>
                    </div>
                </div>

                @guest
                <div class="mt-12">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 rounded-xl hover:bg-gray-100 font-bold text-lg shadow-2xl transform hover:scale-105 transition-all">
                        Get Started
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
                @endguest
            </div>
        </div>
    </body>
</html>
