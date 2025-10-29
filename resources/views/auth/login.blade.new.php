<x-guest-layout>
    @once
     <div class="mx-auto grid w-full max-w-5xl gap-8 rounded-3xl bg-white/60 p-5 shadow-xl backdrop-blur-lg sm:p-8 lg:grid-cols-[1.05fr_minmax(0,1fr)]">
        

        <section class="flex flex-col justify-center rounded-2xl bg-white p-6 shadow-lg sm:p-8">
            <div class="space-y-2 text-center">
                <h2 class="text-2xl font-semibold text-slate-900">Đăng nhập hệ thống</h2>
                <p class="text-sm text-slate-500">Sử dụng tài khoản do phòng Tổ chức cung cấp để truy cập.</p>
            </div>

            <div class="mt-5">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email nội bộ')" class="text-sm font-semibold text-slate-600" />
                        <x-text-input id="email" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label for="password" :value="__('Mật khẩu')" class="text-sm font-semibold text-slate-600" />
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                                    {{ __('Quên mật khẩu?') }}
                                </a>
                            @endif
                        </div>

                        <x-text-input id="password" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="inline-flex items-center gap-2 text-slate-600">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span>{{ __('Ghi nhớ đăng nhập') }}</span>
                        </label>
                        <span class="text-xs text-slate-400">Phiên đăng nhập tự động hết hạn sau 12 giờ.</span>
                    </div>

                    <x-primary-button class="flex w-full items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold uppercase tracking-wider text-white shadow-lg shadow-indigo-200 transition duration-200 hover:bg-indigo-500 focus:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                        {{ __('Đăng nhập') }}
                    </x-primary-button>
                </form>
            </div>
        </section>
    </div>
    @endonce
</x-guest-layout>
