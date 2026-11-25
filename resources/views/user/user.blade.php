@extends('layouts.user')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-6">
  <!-- Welcome Header -->
  <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 text-white">
    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->username }}! 👋</h1>
    <p class="text-indigo-100">Manage your attendance, leaves, and tasks from one place.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Quick Actions -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <a href="{{ route('user.attendance.mark') }}" class="group relative overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-6 text-white shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
            <div class="relative z-10">
              <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <h3 class="font-semibold text-lg">Mark Attendance</h3>
              <p class="text-sm text-indigo-100 mt-1">Check in for today</p>
            </div>
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
          </a>

          <a href="{{ route('user.leaves.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg p-6 text-white shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
            <div class="relative z-10">
              <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <h3 class="font-semibold text-lg">Request Leave</h3>
              <p class="text-sm text-yellow-100 mt-1">Submit leave request</p>
            </div>
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
          </a>

          <a href="{{ route('user.attendance.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg p-6 text-white shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
            <div class="relative z-10">
              <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              <h3 class="font-semibold text-lg">View Attendance</h3>
              <p class="text-sm text-gray-300 mt-1">Check your records</p>
            </div>
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
          </a>
        </div>
      </div>

      <!-- Latest Tasks -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Latest Tasks</h2>
          <a href="{{ route('user.tasks.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">View All →</a>
        </div>
        <div class="space-y-3">
          @forelse($tasks->take(5) as $task)
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
              <div class="flex-1">
                <p class="text-gray-800 dark:text-gray-200 font-medium">{!! Str::limit($task->description, 60) !!}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Assigned {{ $task->created_at->diffForHumans() }}</p>
              </div>
              <div class="ml-4">
                @if($task->status == 'pending')
                  <a href="{{ route('user.tasks.submit', $task->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                    Submit
                  </a>
                @else
                  <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Submitted
                  </span>
                @endif
              </div>
            </div>
          @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
              <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              <p>No tasks assigned yet.</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Sidebar / Profile -->
    <aside class="space-y-6">
      <!-- Profile Card -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="text-center mb-4">
          @if(Auth::user()->avatar)
            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-indigo-100">
          @else
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center mx-auto border-4 border-indigo-100">
              <span class="text-3xl font-bold text-white">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</span>
            </div>
          @endif
        </div>
        <div class="text-center mb-4">
          <h3 class="font-bold text-lg text-gray-800 dark:text-gray-200">{{ Auth::user()->username }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
          <span class="inline-block mt-2 px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-semibold">
            {{ ucfirst(Auth::user()->role) }}
          </span>
        </div>

        <div class="space-y-2">
          <a href="{{ route('profile.edit') }}" class="flex items-center justify-center w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Edit Profile
          </a>
          <a href="{{ route('user.leaves.index') }}" class="flex items-center justify-center w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            View Leaves
          </a>
          <a href="{{ route('user.tasks.index') }}" class="flex items-center justify-center w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            View All Tasks
          </a>
        </div>
      </div>
    </aside>
  </div>
</div>
@endsection
