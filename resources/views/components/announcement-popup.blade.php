<div x-data="announcementPopup()" x-init="loadAnnouncements" class="relative">
    <!-- Announcement Popup -->
    <div x-show="showAnnouncement && currentAnnouncement" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[999999] overflow-y-auto"
         style="display: none;">
        
        <!-- Backdrop with Blur -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-md" @click="currentAnnouncement.is_dismissible ? dismissAnnouncement() : null"></div>
        
        <!-- Popup Container - Responsive -->
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <!-- Popup Card -->
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-full sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 transform translate-y-full sm:translate-y-0 sm:scale-95"
                 class="relative w-full sm:max-w-lg md:max-w-2xl transform transition-all">
                
                <div class="relative rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] sm:max-h-[85vh] flex flex-col"
                     :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-gray-100'">
                    
                    <!-- Close button -->
                    <button x-show="currentAnnouncement.is_dismissible" 
                            @click="dismissAnnouncement()"
                            class="absolute top-3 right-3 sm:top-4 sm:right-4 z-10 p-2.5 rounded-full transition-all duration-200 shadow-lg group hover:scale-105"
                            :class="darkMode ? 'glass border border-white/20 hover:border-[#C8102E]/50' : 'bg-white hover:bg-gray-50 border border-gray-200 hover:border-[#C8102E]/30'">
                        <svg class="w-5 h-5 transition-colors" :class="darkMode ? 'text-gray-300 group-hover:text-[#C8102E]' : 'text-gray-600 group-hover:text-[#C8102E]'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <!-- Header with Dynamic Background -->
                    <div x-bind:class="{
                        'from-[#C8102E] to-[#A00E27]': currentAnnouncement.type === 'info',
                        'from-amber-500 to-orange-600': currentAnnouncement.type === 'warning',
                        'from-green-500 to-emerald-600': currentAnnouncement.type === 'success',
                        'from-[#C8102E] to-[#A00E27]': currentAnnouncement.type === 'promotion'
                    }" class="px-5 sm:px-8 py-6 sm:py-7 text-white bg-gradient-to-r flex-shrink-0 relative overflow-hidden">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>
                        
                        <div class="relative flex items-center space-x-3 sm:space-x-4">
                            <!-- Icon with Glass Effect -->
                            <div class="flex-shrink-0 w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center shadow-lg"
                                 style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                                <svg x-show="currentAnnouncement.type === 'info'" class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <svg x-show="currentAnnouncement.type === 'warning'" class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <svg x-show="currentAnnouncement.type === 'success'" class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <svg x-show="currentAnnouncement.type === 'promotion'" class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0 pr-12">
                                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-white leading-tight line-clamp-2" x-text="currentAnnouncement.title"></h3>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content - Scrollable with Custom Scrollbar -->
                    <div class="flex-1 overflow-y-auto overscroll-contain custom-scrollbar">
                        <div class="px-5 sm:px-8 py-5 sm:py-7 space-y-4 sm:space-y-6">
                            <!-- Image if available -->
                            <div x-show="currentAnnouncement.image_url" class="w-full">
                                <img :src="currentAnnouncement.image_url" 
                                     alt="Announcement" 
                                     class="w-full h-48 sm:h-64 object-cover rounded-2xl shadow-lg border"
                                     :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                            </div>
                            
                            <!-- Message with Modern Card Design -->
                            <div class="rounded-2xl p-5 sm:p-6 transition-all"
                                 :class="darkMode ? 'glass border border-white/10' : 'bg-gradient-to-br from-gray-50 to-white border border-gray-200'">
                                <p class="leading-relaxed text-sm sm:text-base whitespace-pre-wrap break-words"
                                   :class="darkMode ? 'text-gray-200' : 'text-gray-700'" 
                                   x-text="currentAnnouncement.content"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer with Modern Actions -->
                    <div class="px-5 sm:px-8 py-4 sm:py-5 border-t flex-shrink-0"
                         :class="darkMode ? 'border-white/10 glass' : 'bg-gray-50/80 border-gray-200'">
                        <div class="flex flex-col-reverse sm:flex-row justify-end items-stretch sm:items-center gap-3">
                            <!-- Dismiss button -->
                            <button x-show="currentAnnouncement.is_dismissible"
                                    @click="dismissAnnouncement()"
                                    class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 hover:scale-105 active:scale-95"
                                    :class="darkMode ? 'glass border border-white/20 text-white hover:bg-white/10' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Dismiss
                                </span>
                            </button>
                            
                            <!-- CTA button with Dynamic Colors -->
                            <a x-show="currentAnnouncement.action_button_text && currentAnnouncement.action_button_url"
                               :href="currentAnnouncement.action_button_url"
                               x-bind:class="{
                                   'from-[#C8102E] to-[#A00E27] hover:from-[#A00E27] hover:to-[#8A0C20]': currentAnnouncement.type === 'info',
                                   'from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700': currentAnnouncement.type === 'warning',
                                   'from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700': currentAnnouncement.type === 'success',
                                   'from-[#C8102E] to-[#A00E27] hover:from-[#A00E27] hover:to-[#8A0C20]': currentAnnouncement.type === 'promotion'
                               }"
                               class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-white rounded-xl transition-all duration-200 bg-gradient-to-r flex items-center justify-center shadow-lg hover:scale-105 active:scale-95">
                                <span x-text="currentAnnouncement.action_button_text"></span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        
                        <!-- Multiple announcements indicator -->
                        <div x-show="announcements.length > 1" class="mt-4 pt-4 border-t"
                             :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                            <div class="flex items-center justify-center space-x-2">
                                <template x-for="(announcement, index) in announcements" :key="index">
                                    <button @click="currentIndex = index; currentAnnouncement = announcements[index]; showAnnouncement = true;"
                                            :class="currentIndex === index ? 'bg-[#C8102E] w-8' : (darkMode ? 'bg-gray-600 w-2' : 'bg-gray-300 w-2')"
                                            class="h-2 rounded-full transition-all duration-300 hover:bg-[#C8102E] hover:w-4"></button>
                                </template>
                            </div>
                            <p class="text-center text-xs mt-2"
                               :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                                <span x-text="currentIndex + 1"></span> of <span x-text="announcements.length"></span>
                            </p>
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
                    setTimeout(() => {
                        this.showNextAnnouncement();
                    }, 800);
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
                
                setTimeout(() => {
                    this.showNextAnnouncement();
                }, 400);
                
            } catch (error) {
                console.error('Error dismissing announcement:', error);
            }
        }
    };
}
</script>

<style>
/* Prevent body scroll when announcement is open */
body:has([x-show="showAnnouncement && currentAnnouncement"][style*="display: block"]) {
    overflow: hidden;
}

/* Custom Scrollbar matching the theme */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(200, 16, 46, 0.05);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #C8102E;
    border-radius: 10px;
    transition: background 0.3s ease;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #A00E27;
}

/* Mobile-specific scroll behavior */
@media (max-width: 640px) {
    .overscroll-contain {
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
    }
}

/* Glass effect utilities */
.glass {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.glass-dark {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

:root.light-mode .glass {
    background: rgba(255, 255, 255, 0.7);
    box-shadow: 0 8px 32px 0 rgba(200, 16, 46, 0.1);
}

:root.light-mode .glass-dark {
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 8px 32px 0 rgba(200, 16, 46, 0.1);
}

/* Line clamp utility for title */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth animations */
@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
