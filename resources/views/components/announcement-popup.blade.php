<div x-data="announcementPopup()" x-init="loadAnnouncements" class="relative">
    <!-- Announcement Popup -->
    <div x-show="showAnnouncement && currentAnnouncement" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="currentAnnouncement.is_dismissible ? dismissAnnouncement() : null"></div>
        
        <!-- Popup Container -->
        <div class="flex min-h-full items-center justify-center p-4">
            <!-- Popup Content -->
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all">
                
                <!-- Decorative gradient border -->
                <div class="absolute inset-0 bg-gradient-to-r from-purple-400 via-pink-400 to-purple-400 rounded-2xl opacity-75"></div>
                <div class="relative bg-white rounded-2xl m-[2px]">
                    
                    <!-- Header with gradient background -->
                    <div x-bind:class="{
                        'bg-gradient-to-r from-blue-500 to-blue-600': currentAnnouncement.type === 'info',
                        'bg-gradient-to-r from-yellow-500 to-orange-500': currentAnnouncement.type === 'warning',
                        'bg-gradient-to-r from-green-500 to-emerald-500': currentAnnouncement.type === 'success',
                        'bg-gradient-to-r from-purple-500 to-pink-500': currentAnnouncement.type === 'promotion'
                    }" class="px-8 py-6 text-white relative overflow-hidden">
                        <!-- Background pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white rounded-full"></div>
                            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white rounded-full"></div>
                        </div>
                        
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Animated icon background -->
                                <div class="relative">
                                    <div class="absolute inset-0 bg-white/20 rounded-full blur-lg animate-pulse"></div>
                                    <div class="relative bg-white/25 p-3 rounded-full backdrop-blur-sm">
                                        <svg x-show="currentAnnouncement.type === 'info'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <svg x-show="currentAnnouncement.type === 'warning'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <svg x-show="currentAnnouncement.type === 'success'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <svg x-show="currentAnnouncement.type === 'promotion'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold" x-text="currentAnnouncement.title"></h3>
                                    <p class="text-sm opacity-90 mt-1">
                                        <span x-show="currentAnnouncement.type === 'info'">Information</span>
                                        <span x-show="currentAnnouncement.type === 'warning'">Important Notice</span>
                                        <span x-show="currentAnnouncement.type === 'success'">Good News</span>
                                        <span x-show="currentAnnouncement.type === 'promotion'">Special Offer</span>
                                    </p>
                                </div>
                            </div>
                            <button x-show="currentAnnouncement.is_dismissible" 
                                    @click="dismissAnnouncement()"
                                    class="p-2 hover:bg-white/20 rounded-full transition-colors group">
                                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-8">
                        <!-- Image if available -->
                        <div x-show="currentAnnouncement.image_url" class="mb-6">
                            <img :src="currentAnnouncement.image_url" 
                                 alt="Announcement" 
                                 class="w-full h-64 object-cover rounded-xl shadow-lg">
                        </div>
                        
                        <!-- Message with better typography -->
                        <div class="prose prose-lg max-w-none">
                            <p class="text-gray-700 leading-relaxed text-lg" x-text="currentAnnouncement.content"></p>
                        </div>
                        
                        <!-- Decorative divider -->
                        <div class="my-8 flex items-center">
                            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex justify-end items-center space-x-4">
                            <!-- Dismiss button -->
                            <button x-show="currentAnnouncement.is_dismissible"
                                    @click="dismissAnnouncement()"
                                    class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200 hover:shadow-md">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Dismiss
                                </span>
                            </button>
                            
                            <!-- CTA button with gradient and animation -->
                            <a x-show="currentAnnouncement.action_button_text && currentAnnouncement.action_button_url"
                               :href="currentAnnouncement.action_button_url"
                               x-bind:class="{
                                   'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800': currentAnnouncement.type === 'info',
                                   'bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700': currentAnnouncement.type === 'warning',
                                   'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700': currentAnnouncement.type === 'success',
                                   'bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700': currentAnnouncement.type === 'promotion'
                               }"
                               class="px-8 py-3 text-sm font-semibold text-white rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-xl flex items-center group">
                                <span x-text="currentAnnouncement.action_button_text"></span>
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Footer indicator for multiple announcements -->
                    <div x-show="announcements.length > 1" class="px-8 pb-6">
                        <div class="flex items-center justify-center space-x-2">
                            <template x-for="(announcement, index) in announcements" :key="index">
                                <button @click="currentIndex = index; currentAnnouncement = announcements[index]; showAnnouncement = true;"
                                        :class="currentIndex === index ? 'bg-gray-800 w-8' : 'bg-gray-300 w-2'"
                                        class="h-2 rounded-full transition-all duration-300 hover:bg-gray-600"></button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function announcementPopup() {
    return {
        announcements: [],
        currentAnnouncement: null,
        showAnnouncement: false,
        currentIndex: 0,

        async loadAnnouncements() {
            try {
                const response = await fetch('{{ route('student.announcements.active') }}');
                const data = await response.json();
                this.announcements = data.announcements;
                
                if (this.announcements.length > 0) {
                    // Add a small delay before showing the first announcement
                    setTimeout(() => {
                        this.showNextAnnouncement();
                    }, 500);
                }
            } catch (error) {
                console.error('Error loading announcements:', error);
            }
        },

        showNextAnnouncement() {
            if (this.currentIndex < this.announcements.length) {
                this.currentAnnouncement = this.announcements[this.currentIndex];
                this.showAnnouncement = true;
            }
        },

        async dismissAnnouncement() {
            if (!this.currentAnnouncement) return;

            try {
                await fetch(`{{ url('student/announcements') }}/${this.currentAnnouncement.id}/dismiss`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                this.showAnnouncement = false;
                this.currentIndex++;
                
                // Show next announcement after a short delay
                setTimeout(() => {
                    this.showNextAnnouncement();
                }, 500);
                
            } catch (error) {
                console.error('Error dismissing announcement:', error);
            }
        }
    };
}
</script>

<style>
/* Additional styles for better animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
