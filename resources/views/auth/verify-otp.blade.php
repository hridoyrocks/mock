<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - CD IELTS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        {{-- Left Side - Branding (Desktop Only) --}}
        <div class="hidden lg:flex lg:w-2/5 bg-gradient-to-br from-red-500 to-rose-600 items-center justify-center px-12">
            <div class="text-white text-center">
                <div class="mb-8">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto backdrop-blur-sm">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-4xl font-bold mb-4">Almost There!</h1>
                <p class="text-lg opacity-90">Verify your email to start your IELTS journey</p>
            </div>
        </div>

        {{-- Right Side - Form --}}
        <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-md">
                {{-- Mobile Header --}}
                <div class="lg:hidden text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Email Verification</h1>
                    <p class="text-sm text-gray-600 mt-1">Enter the code sent to your email</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 lg:p-8">
                    {{-- Desktop Header --}}
                    <div class="hidden lg:block text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Verify Your Email</h2>
                        <p class="text-sm text-gray-600 mt-1">We've sent a 6-digit code to</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $email }}</p>
                    </div>

                    {{-- Mobile Email Display --}}
                    <div class="lg:hidden bg-gray-50 rounded-lg p-3 mb-5 text-center">
                        <p class="text-xs text-gray-500">Code sent to</p>
                        <p class="text-sm font-medium text-gray-900">{{ $email }}</p>
                    </div>

                    {{-- Success/Error Messages --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-3 py-2 rounded-lg text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-3 py-2 rounded-lg text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- OTP Form --}}
                    <form method="POST" action="{{ route('auth.otp.verify') }}" id="otp-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        {{-- OTP Input --}}
                        <div class="mb-5">
                            <label class="block text-xs font-medium text-gray-700 mb-3 text-center">
                                Enter verification code
                            </label>
                            <div class="flex justify-center gap-2 sm:gap-3">
                                @for($i = 1; $i <= 6; $i++)
                                    <input type="text" 
                                           name="otp_digit_{{ $i }}" 
                                           id="otp_{{ $i }}"
                                           maxlength="1" 
                                           pattern="[0-9]"
                                           inputmode="numeric"
                                           class="w-10 h-10 sm:w-12 sm:h-12 text-center text-lg font-semibold border-2 border-gray-300 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-500 transition"
                                           onkeyup="moveToNext(this, 'otp_{{ $i + 1 }}')"
                                           onkeydown="moveToPrev(event, 'otp_{{ $i - 1 }}')"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                           @if($i === 1) autofocus @endif>
                                @endfor
                            </div>
                            <input type="hidden" name="otp" id="otp" value="">
                            
                            @error('otp')
                                <p class="mt-2 text-xs text-red-600 text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Timer --}}
                        <div class="text-center mb-5">
                            <p class="text-xs text-gray-600">
                                Code expires in: <span id="timer" class="font-mono font-medium text-red-600">5:00</span>
                            </p>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" 
                                id="verify-btn"
                                class="w-full py-2.5 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                            Verify Email
                        </button>

                        {{-- Resend Section --}}
                        <div class="mt-5 text-center">
                            <p class="text-xs text-gray-600">
                                Didn't receive the code?
                                <button type="button" 
                                        id="resend-btn"
                                        onclick="resendOTP()"
                                        class="text-red-600 hover:text-red-500 font-medium disabled:text-gray-400 disabled:cursor-not-allowed"
                                        disabled>
                                    Resend Code
                                </button>
                            </p>
                            <p id="resend-message" class="text-xs mt-1"></p>
                        </div>
                    </form>

                    {{-- Divider --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-3">Having trouble?</p>
                            <div class="flex justify-center space-x-4 text-xs">
                                <a href="{{ route('login') }}" class="text-red-600 hover:text-red-500">Back to Login</a>
                                <span class="text-gray-300">•</span>
                                <a href="#" class="text-red-600 hover:text-red-500">Contact Support</a>
                            </div>
                        </div>
                    </div>

                    {{-- Debug Info --}}
                    @if(config('app.debug'))
                        <div class="mt-4 text-center">
                            <details class="text-xs text-gray-400">
                                <summary class="cursor-pointer">Debug Info</summary>
                                <p class="mt-1">Check logs: storage/logs/laravel.log</p>
                                <p>Database: otp_verifications table</p>
                            </details>
                        </div>
                    @endif
                </div>

                {{-- Footer Info --}}
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Check your spam folder if you don't see the email
                    </p>
                    <p class="text-xs text-gray-400 mt-2">
                        By verifying, you agree to our 
                        <a href="#" class="hover:text-gray-600">Terms</a> and 
                        <a href="#" class="hover:text-gray-600">Privacy Policy</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // OTP Input Handling
        function moveToNext(current, nextFieldID) {
            if (current.value.length >= current.maxLength) {
                if (nextFieldID) {
                    const nextField = document.getElementById(nextFieldID);
                    if (nextField) {
                        nextField.focus();
                    }
                }
            }
            updateOTPValue();
        }

        function moveToPrev(event, prevFieldID) {
            if (event.key === 'Backspace' && event.target.value === '') {
                event.preventDefault();
                if (prevFieldID) {
                    const prevField = document.getElementById(prevFieldID);
                    if (prevField) {
                        prevField.focus();
                        prevField.select();
                    }
                }
            }
            setTimeout(updateOTPValue, 10);
        }

        function updateOTPValue() {
            let otpValue = '';
            for (let i = 1; i <= 6; i++) {
                const digit = document.getElementById('otp_' + i).value;
                otpValue += digit;
            }
            document.getElementById('otp').value = otpValue;
            
            // Enable/disable submit button
            const verifyBtn = document.getElementById('verify-btn');
            verifyBtn.disabled = otpValue.length !== 6;
        }

        // Timer
        let timeLeft = 300;
        const timerElement = document.getElementById('timer');
        let timerInterval;
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerElement.textContent = 'Expired';
                document.getElementById('resend-btn').disabled = false;
                document.getElementById('verify-btn').disabled = true;
            } else {
                timeLeft--;
            }
        }
        
        timerInterval = setInterval(updateTimer, 1000);
        updateTimer();

        // Enable resend after 60 seconds
        setTimeout(() => {
            const resendBtn = document.getElementById('resend-btn');
            if (resendBtn && timeLeft > 0) {
                resendBtn.disabled = false;
            }
        }, 60000);

        // Resend OTP
        function resendOTP() {
            const resendBtn = document.getElementById('resend-btn');
            const resendMessage = document.getElementById('resend-message');
            
            resendBtn.disabled = true;
            resendMessage.textContent = 'Sending...';
            resendMessage.className = 'text-xs mt-1 text-gray-500';
            
            fetch('{{ route('auth.otp.resend') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: '{{ $email }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset timer
                    timeLeft = 300;
                    clearInterval(timerInterval);
                    timerInterval = setInterval(updateTimer, 1000);
                    
                    // Clear fields
                    for (let i = 1; i <= 6; i++) {
                        document.getElementById('otp_' + i).value = '';
                    }
                    document.getElementById('otp').value = '';
                    document.getElementById('otp_1').focus();
                    
                    resendMessage.textContent = '✓ New code sent successfully!';
                    resendMessage.className = 'text-xs mt-1 text-green-600';
                    
                    setTimeout(() => {
                        resendBtn.disabled = false;
                        resendMessage.textContent = '';
                    }, 60000);
                } else {
                    resendMessage.textContent = data.message || 'Failed to send';
                    resendMessage.className = 'text-xs mt-1 text-red-600';
                    resendBtn.disabled = false;
                }
            })
            .catch(error => {
                resendMessage.textContent = 'Network error';
                resendMessage.className = 'text-xs mt-1 text-red-600';
                resendBtn.disabled = false;
            });
        }

        // Paste handling
        document.addEventListener('paste', function(e) {
            if (e.target.id && e.target.id.startsWith('otp_')) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const digits = paste.replace(/[^0-9]/g, '').slice(0, 6);
                
                if (digits.length > 0) {
                    for (let i = 0; i < digits.length && i < 6; i++) {
                        document.getElementById('otp_' + (i + 1)).value = digits[i];
                    }
                    updateOTPValue();
                    
                    const lastIndex = Math.min(digits.length, 6);
                    const focusField = document.getElementById('otp_' + (lastIndex < 6 ? lastIndex + 1 : lastIndex));
                    if (focusField) focusField.focus();
                }
            }
        });

        // Initialize
        updateOTPValue();
    </script>
</body>
</html>