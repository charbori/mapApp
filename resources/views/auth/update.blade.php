<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/api/map">
                <h3 style="font-weight: bold; font-size:24px;">Sport Record</h3>
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if (Auth::check() !== false)

        <form method="POST" enctype="multipart/form-data" action="{{ route('update') }}">
            @csrf
            <!-- Name -->
            <div class="flex items-center justify-center mt-4" height="96">
                <div>
                    <x-input-label for="name" :value="__('Name')" />

                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />

                    <x-input-error :messages="$errors->get('name')" class="mt-2" />

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>
                <div class="p-2">
                    <x-input-label for="name" :value="__('Profile')" />
                    <input id="hidden-input" type="file" name="photos[]" multiple class="hidden" />
                    @if (getMobile())
                        @include('components.image-upload-mo-s')
                    @else
                        @include('components.image-upload-mo-s')
                    @endif
                    <input type="hidden" name="type" value="user_img"/>
                </div>
            </div>

            <!-- Name -->
            <div class="flex items-center justify-center" height="96">
                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    </a>

                    <x-primary-button class="ml-4 mt-4">
                        {{ __('Modify') }}
                    </x-primary-button>
                </div>
            </div>
        </form>

        @endif
    </x-auth-card>
</x-guest-layout>
