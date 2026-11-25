
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Photo') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your profile picture.") }}
        </p>
    </header>

    {{-- Note: We are pointing to a new route 'profile.avatar.update' --}}
    <form method="post" action="{{ route('profile.avatar.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            {{-- Current Image Preview --}}
            @if ($user->avatar)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Current Profile Photo" class="w-32 h-32 rounded-full object-cover border border-gray-300 shadow-sm mx-auto sm:mx-0">
                </div>
            @else
                <div class="mb-4 w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 mx-auto sm:mx-0">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            @endif

            <x-input-label for="avatar" :value="__('Select New Photo')" />
            
            {{-- File Input --}}
            <input id="avatar" name="avatar" type="file" class="mt-1 block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100
            "/>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Photo') }}</x-primary-button>

            @if (session('status') === 'avatar-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>