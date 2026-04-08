@section('title', 'Create Account')
<x-guest-layout>
    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Create Your Account</h1>
        <p class="text-gray-500 text-sm">Join thousands of readers discovering new books</p>
    </div>

    {{-- Register Form --}}
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Email Address --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400 text-sm"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-white input-focus transition-all"
                       placeholder="Enter your email">
            </div>
            @error('email')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400 text-sm"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-white input-focus transition-all"
                       placeholder="Create a password" oninput="checkPasswordStrength(this.value)">
                <button type="button" onclick="togglePassword('password')" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-eye text-sm" id="password-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
            
            {{-- Password Strength Indicator --}}
            <div class="mt-2" id="password-strength" style="display: none;">
                <div class="flex items-center gap-2 mb-1">
                    <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div id="strength-bar" class="h-full transition-all duration-300" style="width: 0%;"></div>
                    </div>
                    <span id="strength-text" class="text-xs font-medium"></span>
                </div>
                <ul class="text-xs text-gray-500 space-y-0.5">
                    <li id="req-length" class="flex items-center gap-1">
                        <i class="fas fa-circle text-[6px]"></i>
                        <span>At least 8 characters</span>
                    </li>
                    <li id="req-number" class="flex items-center gap-1">
                        <i class="fas fa-circle text-[6px]"></i>
                        <span>Contains a number</span>
                    </li>
                    <li id="req-special" class="flex items-center gap-1">
                        <i class="fas fa-circle text-[6px]"></i>
                        <span>Contains a special character</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                Confirm Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400 text-sm"></i>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-white input-focus transition-all"
                       placeholder="Confirm your password">
                <button type="button" onclick="togglePassword('password_confirmation')" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-eye text-sm" id="password_confirmation-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Terms Agreement --}}
        <div class="flex items-start gap-3">
            <input type="checkbox" id="terms" name="terms" required
                   class="mt-1 w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
            <label for="terms" class="text-sm text-gray-600 cursor-pointer">
                I agree to the 
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Terms of Service</a>
                and 
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Privacy Policy</a>
            </label>
        </div>

        {{-- Submit Button --}}
        <button type="submit" 
                class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3.5 rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 group">
            <span>Create Account</span>
            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </button>
    </form>

    {{-- Divider --}}
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-500">or sign up with</span>
        </div>
    </div>

    {{-- Social Login --}}
    <div class="grid grid-cols-2 gap-3">
        <button type="button" class="flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
            <span class="text-sm font-medium text-gray-700">Google</span>
        </button>
        <button type="button" class="flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
            <img src="https://www.svgrepo.com/show/475647/apple-color.svg" alt="Apple" class="w-5 h-5">
            <span class="text-sm font-medium text-gray-700">Apple</span>
        </button>
    </div>

    {{-- Login Link --}}
    <div class="text-center mt-8">
        <p class="text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                Sign in
            </a>
        </p>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength(password) {
            const strengthDiv = document.getElementById('password-strength');
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            const reqLength = document.getElementById('req-length');
            const reqNumber = document.getElementById('req-number');
            const reqSpecial = document.getElementById('req-special');

            if (password.length > 0) {
                strengthDiv.style.display = 'block';
            }

            let strength = 0;
            const hasLength = password.length >= 8;
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            if (hasLength) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;

            // Update requirement indicators
            updateRequirement(reqLength, hasLength);
            updateRequirement(reqNumber, hasNumber);
            updateRequirement(reqSpecial, hasSpecial);

            // Update strength bar
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
            const texts = ['Weak', 'Fair', 'Good', 'Strong'];
            const widths = ['25%', '50%', '75%', '100%'];

            strengthBar.className = 'h-full transition-all duration-300 ' + colors[strength];
            strengthBar.style.width = widths[strength];
            strengthText.textContent = texts[strength];
            strengthText.className = 'text-xs font-medium ' + (strength >= 2 ? 'text-green-600' : 'text-gray-500');
        }

        function updateRequirement(element, met) {
            const icon = element.querySelector('i');
            if (met) {
                icon.className = 'fas fa-check text-green-500 text-xs';
                element.classList.add('text-green-600');
                element.classList.remove('text-gray-500');
            } else {
                icon.className = 'fas fa-circle text-[6px]';
                element.classList.remove('text-green-600');
                element.classList.add('text-gray-500');
            }
        }
    </script>
</x-guest-layout>
