@extends('layouts.app')

{{-- 2. Define the 'header' section (if your layout has @yield('header')) --}}
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Profile') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex items-start space-x-6">
                    <!-- Left: Profile Info Form -->
                    <div class="flex-1">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <!-- Right: Profile Photo -->
                    {{-- <div class="w-48 flex-shrink-0">
                        @include('profile.partials.update-profile-photo-form')
                    </div> --}}
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
