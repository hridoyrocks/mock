<x-layout>
    <x-slot:title>Verify Email - IELTS Mock Test</x-slot>
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                {{-- Header --}}
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Verify Your Email</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        We've sent a verification code to<br>
                        <span class="font-medium text-gray-900">{{ $email }}</span>
                    </p>
                </div>

                {{-- OTP Form --}}
                <form method="POST" action="{{ route('auth.otp.verify') }}" class="mt-8 space-y-6">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    {{-- OTP Input --}}
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700 text-center mb-3">
                            Enter 6-digit code
                        </label>
                        <div class="flex justify-center space-x-3" id="otp-inputs">
                            @for($i = 1; $i <= 6; $i++)
                                <input type="text" 
                                       name="otp_digit_{{ $i }}" 
                                       id="otp_{{ $i }}"
                                       maxlength="1" 
                                       class="w-12 h-12 text-center text-xl font-semibold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                       onkeyup="moveToNext(this, 'otp_{{ $i + 1 }}')"
                                       onkeydown="moveToPrev(event, 'otp_{{ $i - 1 }}')"
                                       @if($i === 1) autofocus @endif>
                            @endfor
                        </div>
                        <input type="hidden" name="otp" id="otp" value="">
                        
                        @error('otp')
                            <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Timer --}}
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Code expires in: <span id="timer" class="font-mono font-medium text-blue-600">5:00</span>
                        </p>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Verify Email
                        </button>
                    </div>

                    {{-- Resend --}}
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Didn't receive the code?
                            <button type="button" 
                                    id="resend-btn"
                                    onclick="resendOTP()"
                                    class="font-medium text-blue-600 hover:text-blue-500 disabled:text-gray-400 disabled:cursor-not-allowed"
                                    disabled>
                                Resend
                            </button>
                        </p>
                    </div>
                </form>

                {{-- Help Text --}}
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Check your spam folder if you don't see the email.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // OTP Input Handling
        function moveToNext(current, nextFieldID) {
            if (current.value.length >= current.maxLength) {
                if (nextFieldID) {
                    document.getElementById(nextFieldID).focus();
                }
            }
            updateOTPValue();
        }

        function moveToPrev(event, prevFieldID) {
            if (event.key === 'Backspace' && event.target.value === '') {
                if (prevFieldID) {
                    document.getElementById(prevFieldID).focus();
                }
            }
            updateOTPValue();
        }

        function updateOTPValue() {
            let otpValue = '';
            for (let i = 1; i <= 6; i++) {
                otpValue += document.getElementById('otp_' + i).value;
            }
            document.getElementById('otp').value = otpValue;
        }

        // Timer
        let timeLeft = 300; // 5 minutes in seconds
        const timerElement = document.getElementById('timer');
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                timerElement.textContent = 'Expired';
                timerElement.classList.remove('text-blue-600');
                timerElement.classList.add('text-red-600');
                document.getElementById('resend-btn').disabled = false;
            } else {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            }
        }
        
        updateTimer();

        // Enable resend after 60 seconds
        setTimeout(() => {
            document.getElementById('resend-btn').disabled = false;
        }, 60000);

        // Resend OTP
        function resendOTP() {
            fetch('{{ route('auth.otp.resend') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                    updateTimer();
                    
                    // Disable resend button
                    document.getElementById('resend-btn').disabled = true;
                    
                    // Show success message
                    alert('New OTP sent successfully!');
                }
            });
        }

        // Auto submit when all fields are filled
        document.addEventListener('input', function() {
            const otpValue = document.getElementById('otp').value;
            if (otpValue.length === 6) {
                document.querySelector('form').submit();
            }
        });
    </script>
    @endpush
</x-layout>