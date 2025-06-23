<x-admin::layout>
    <x-slot name="title">{{ __('Create Admin') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Admin') }}</x-slot>
    <x-slot name="page_slug">admin</x-slot>
    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Admin') }}</h2>
                <x-admin.primary-link href="{{ route('am.admin.index') }}">{{ __('Back') }} <i data-lucide="undo-2"
                        class="w-4 h-4"></i> </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('am.admin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="space-y-2 ">
                            <p class="label">{{ __('Name') }}</p>
                            <label class="input flex items-center px-2 ">
                                <input type="text" placeholder="Name" value="{{ old('name') }}" name="name"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Email') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="example@gmail.com" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Password') }}</p>
                            <label class="input relative flex items-center gap-2">
                               
                                <input :type="$store.password.showPassword ? 'text' : 'password'" name="password"
                                    placeholder="Password" class="flex-1" />
                                <button type="button" class="pr-2"
                                    @click="$store.password.showPassword = !$store.password.showPassword ; $nextTick(() => lucide.createIcons())">
                                    <i :data-lucide="$store.password.showPassword ? 'eye-off' : 'eye'"
                                        class="w-4 h-4"></i>
                                </button>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />

                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Confirm Password') }}</p>
                            <label class="input flex items-center gap-2">
                                <input :type="$store.password.showPassword ? 'text' : 'password'"
                                    name="password_confirmation" placeholder="Confirm Password" class="flex-1" />
                                <button type="button" class="pr-2"
                                    @click="$store.password.showPassword = !$store.password.showPassword ; $nextTick(() => lucide.createIcons())">
                                    <i :data-lucide="$store.password.showPassword ? 'eye-off' : 'eye'"
                                        class="w-4 h-4 "></i>
                                </button>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <p class="label">{{ __('Image') }}</p>
                            <input type="file" name="image" class="filepond" id="image"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Create') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
    </section>
    @push('js')
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"]);
            });
        </script>
    @endpush
</x-admin::layout>
