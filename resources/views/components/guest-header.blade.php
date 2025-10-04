<!-- Guest Header Component -->
<header class="fixed top-0 left-0 right-0 z-50 bg-white/10 backdrop-blur-md border-b border-white/20">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('welcome') }}" class="flex items-center">
                @if($websiteSettings->site_logo)
                    <img src="{{ $websiteSettings->logo_url }}" alt="{{ $websiteSettings->site_title }}" class="h-12 w-auto">
                @else
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-xl">{{ substr($websiteSettings->site_title, 0, 1) }}</span>
                    </div>
                @endif
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-red-600 transition-colors font-medium">Home</a>
                <a href="#about" class="text-gray-700 hover:text-red-600 transition-colors font-medium">About</a>
                <a href="#tests" class="text-gray-700 hover:text-red-600 transition-colors font-medium">Mock Tests</a>
                <a href="#pricing" class="text-gray-700 hover:text-red-600 transition-colors font-medium">Pricing</a>
                <a href="#contact" class="text-gray-700 hover:text-red-600 transition-colors font-medium">Contact</a>
            </div>
            
            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-gray-700 bg-white/50 backdrop-blur-sm border border-gray-200 rounded-lg hover:bg-white/70 transition-all font-medium">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                        Get Started
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                        Dashboard
                    </a>
                @endguest
            </div>
            
            <!-- Mobile Menu Button -->
            <button 
                onclick="toggleMobileMenu()"
                class="md:hidden text-gray-700 p-2 rounded-lg hover:bg-white/50 transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden mt-4 py-4 border-t border-white/20">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-red-600 transition-colors font-medium py-2">Home</a>
                <a href="#about" class="text-gray-700 hover:text-red-600 transition-colors font-medium py-2">About</a>
                <a href="#tests" class="text-gray-700 hover:text-red-600 transition-colors font-medium py-2">Mock Tests</a>
                <a href="#pricing" class="text-gray-700 hover:text-red-600 transition-colors font-medium py-2">Pricing</a>
                <a href="#contact" class="text-gray-700 hover:text-red-600 transition-colors font-medium py-2">Contact</a>
                
                <div class="pt-4 space-y-3 border-t border-white/20">
                    @guest
                        <a href="{{ route('login') }}" class="block w-full px-5 py-2.5 text-center text-gray-700 bg-white/50 backdrop-blur-sm border border-gray-200 rounded-lg hover:bg-white/70 transition-all font-medium">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="block w-full px-5 py-2.5 text-center bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            Get Started
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block w-full px-5 py-2.5 text-center bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }
</script>

<style>
    /* Additional glass effect styles */
    header {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
</style>
