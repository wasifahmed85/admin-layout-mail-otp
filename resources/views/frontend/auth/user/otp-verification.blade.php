<x-frontend::layout>

    <x-slot name="title">
        {{ __('Verify Your Email Address') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Verify Your Email Address') }}
    </x-slot>

    <x-slot name="page_slug">
        verify-otp
    </x-slot>

    <section
        class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 flex items-center justify-center ">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-40 dark:opacity-20">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
                <div class="shape shape-6"></div>
            </div>
        </div>

        <div
            class="max-w-md w-full space-y-8 p-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 relative">
            <a href="{{ url('/') }}"
                class="flex items-center justify-center absolute -top-15 left-0   py-3 rounded-md animate-scalePulse text-gray-700 gap-2">
                <i data-lucide="arrow-left"></i>
                <span>Back To Home</span>
            </a>
            @auth('web')
                <x-user.profile-navlink route="{{ route('logout') }}" logout='true' name="{{ __('Sign Out') }}" />
            @endauth
            <form method="POST" action="{{ route('verify-otp', ['email' => $email]) }}" class="space-y-6"
                id="otp-form">
                @csrf

                <h1 class="text-3xl font-extrabold text-center text-gray-900 dark:text-white">
                    {{ __('Verify Your Email Address') }}
                </h1>

                <p class="mt-4 text-center text-gray-600 dark:text-gray-400">
                    {{ __('Please enter the 6-digit verification code we sent to your email address.') }}
                </p>

                @if (session('status'))
                    <div class="text-sm font-medium text-green-600 dark:text-green-400 text-center">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->has('resend_timer'))
                    <div class="text-sm font-medium text-red-600 dark:text-red-400 text-center">
                        {{ $errors->first('resend_timer') }}
                    </div>
                @endif

                <div class="mt-6">
                    @if (isset($isForgot))
                        <input id="forgot" name="forgot" type="hidden" value="{{ $isForgot }}"
                            autocomplete="forgot">
                    @endif

                    {{-- Assuming x-input-label is a Blade component you have --}}
                    <label for="otp"
                        class="block text-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Verification Code') }}</label>


                    <div class="flex justify-center space-x-2 otp-inputs">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="text" id="otp-{{ $i }}" name="otp_digit[]" maxlength="1"
                                inputmode="numeric" autocomplete="one-time-code"
                                class="otp-digit w-12 h-12 text-center text-2xl font-bold rounded-md border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200 ease-in-out shadow-sm"
                                style="caret-color: transparent;" onfocus="this.select()">
                        @endfor
                        {{-- Hidden input to store the combined OTP value for submission --}}
                        <input type="hidden" name="otp" id="hidden-otp-input">
                    </div>

                    {{-- Assuming x-input-error is a Blade component you have --}}
                    @error('otp')
                        <p class="mt-2 text-center text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div
                    class="flex flex-col sm:flex-row items-center justify-between mt-8 space-y-4 sm:space-y-0 sm:space-x-4">
                    <button type="button" id="resend-otp-button"
                        class="w-full sm:w-auto flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed"
                        data-resend-route="{{ route('otp-resend', isset($isForgot) ? ['forgot' => $isForgot, 'email' => $email] : ['email' => $email]) }}"
                        @if (isset($lastOtpSentAt)) data-last-sent-timestamp="{{ $lastOtpSentAt }}" @endif>
                        {{ __('Resend Code') }}
                    </button>

                    {{-- Assuming x-primary-button is a Blade component you have --}}
                    <button type="submit py-6"
                        class="w-full sm:w-auto flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                        {{ __('Verify') }}
                    </button>
                </div>
            </form>
        </div>

        @push('js')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const otpInputs = document.querySelectorAll('.otp-digit');
                    const hiddenOtpInput = document.getElementById('hidden-otp-input');
                    const form = document.getElementById('otp-form');
                    const resendButton = document.getElementById('resend-otp-button');
                    const resendCooldownSeconds = 60; // 1 minute cooldown

                    let countdownInterval;

                    // Function to update the hidden OTP input
                    function updateHiddenOtp() {
                        let combinedOtp = '';
                        otpInputs.forEach(input => {
                            combinedOtp += input.value;
                        });
                        hiddenOtpInput.value = combinedOtp;
                    }

                    // Function to start the resend cooldown timer
                    function startResendCountdown(lastSentTimestamp) {
                        const now = Math.floor(Date.now() / 1000); // Current time in seconds
                        const elapsedTime = now - lastSentTimestamp;
                        let remainingTime = resendCooldownSeconds - elapsedTime;

                        if (remainingTime <= 0) {
                            // Cooldown has passed, enable button immediately
                            resendButton.disabled = false;
                            resendButton.textContent = '{{ __('Resend Code') }}';
                            return;
                        }

                        resendButton.disabled = true;
                        resendButton.textContent = `{{ __('Resend Code') }} (${remainingTime}s)`;

                        clearInterval(countdownInterval); // Clear any existing interval

                        countdownInterval = setInterval(() => {
                            remainingTime--;
                            if (remainingTime > 0) {
                                resendButton.textContent = `{{ __('Resend Code') }} (${remainingTime}s)`;
                            } else {
                                clearInterval(countdownInterval);
                                resendButton.disabled = false;
                                resendButton.textContent = '{{ __('Resend Code') }}';
                            }
                        }, 1000);
                    }

                    // Initialize countdown if a last sent timestamp is available
                    const initialLastSentTimestamp = resendButton.dataset.lastSentTimestamp;
                    if (initialLastSentTimestamp) {
                        startResendCountdown(parseInt(initialLastSentTimestamp));
                    }

                    // Add event listener for the resend button (using Axios)
                    resendButton.addEventListener('click', async () => {
                        if (resendButton.disabled) {
                            return; // Do nothing if button is disabled
                        }

                        // Temporarily disable the button to prevent multiple clicks
                        resendButton.disabled = true;
                        resendButton.textContent = '{{ __('Sending...') }}';

                        try {
                            const resendRoute = resendButton.dataset.resendRoute;
                            const forgotValue = document.getElementById('forgot')?.value;

                            // Using Axios to send the POST request
                            const response = await axios.post(resendRoute, {
                                forgot: forgotValue
                            });

                            // Axios automatically parses JSON into response.data
                            const data = response.data;

                            // Axios throws an error for non-2xx status codes, so response.ok is not needed here
                            if (data.success) {
                                toastr.success(data.message || 'OTP sent successfully!');
                                if (data.last_sent_at) {
                                    startResendCountdown(data.last_sent_at);
                                } else {
                                    startResendCountdown(Math.floor(Date.now() / 1000));
                                }
                            } else {
                                toastr.error(data.message || 'Failed to send OTP. Please try again.');
                                resendButton.disabled = false;
                                resendButton.textContent = '{{ __('Resend Code') }}';
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            // Axios error response object has more details
                            if (error.response) {
                                // The request was made and the server responded with a status code
                                // that falls out of the range of 2xx
                                if (error.response.status === 401) {
                                    toastr.error('You are not authenticated. Please log in again.');
                                    window.location.href = '/login';
                                } else if (error.response.status === 429) {
                                    // Specific handling for too many requests
                                    toastr.error(error.response.data.message ||
                                        'Too many requests. Please try again later.');
                                } else {
                                    toastr.error(error.response.data.message ||
                                        'An error occurred while sending OTP. Please try again.');
                                }
                            } else if (error.request) {
                                // The request was made but no response was received
                                toastr.error('No response from server. Please check your network connection.');
                            } else {
                                // Something happened in setting up the request that triggered an Error
                                toastr.error('An unexpected error occurred. Please try again.');
                            }
                            resendButton.disabled = false;
                            resendButton.textContent = '{{ __('Resend Code') }}';
                        }
                    });


                    otpInputs.forEach((input, index) => {
                        input.addEventListener('input', (e) => {
                            if (e.data && !/^\d$/.test(e.data)) {
                                input.value = '';
                                updateHiddenOtp();
                                return;
                            }

                            if (input.value.length === 1 && index < otpInputs.length - 1) {
                                otpInputs[index + 1].focus();
                            }
                            updateHiddenOtp();
                        });

                        input.addEventListener('keydown', (e) => {
                            if (e.key === 'Backspace') {
                                if (input.value.length === 0 && index > 0) {
                                    otpInputs[index - 1].focus();
                                }
                            }
                        });

                        if (index === 0) {
                            input.addEventListener('paste', (e) => {
                                e.preventDefault();
                                const pasteData = e.clipboardData.getData('text').trim();
                                if (pasteData.length === 6 && /^\d+$/.test(pasteData)) {
                                    otpInputs.forEach((otpInput, i) => {
                                        otpInput.value = pasteData[i];
                                    });
                                    otpInputs[otpInputs.length - 1].focus();
                                    updateHiddenOtp();
                                } else if (pasteData.length > 0) {
                                    otpInputs.forEach(otpInput => otpInput.value = '');
                                    alert('Please paste a 6-digit numeric code.');
                                    updateHiddenOtp();
                                }
                            });
                        }
                    });

                    form.addEventListener('submit', () => {
                        updateHiddenOtp();
                    });

                    updateHiddenOtp();
                });
            </script>
        @endpush
    </section>

</x-frontend::layout>
