{{-- resources/views/components/test-timer.blade.php --}}
{{-- Updated version to integrate in user bar --}}
@props([
    'attempt',
    'autoSubmitFormId' => null,
    'warningTime' => 300,
    'dangerTime' => 60,
    'position' => 'integrated' // integrated, top-right, top-left, etc.
])

@php
    $testDuration = $attempt->testSet->section->time_limit;
    $attemptStartTime = $attempt->start_time;
@endphp

@if($position === 'integrated')
    {{-- This will be integrated directly in the user bar --}}
    <div class="timer-integrated" id="timer-integrated">
        <div class="timer-minimalist" id="universal-timer-display-integrated">
            <svg class="timer-icon" viewBox="0 0 48 48">
                <linearGradient id="ardn4qMWM6qJppYdTWAANa_wrIwUNhk1J4k_gr1" x1="9.858" x2="38.142" y1="9.858" y2="38.142" gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#889097"></stop>
                    <stop offset="1" stop-color="#64717c"></stop>
                </linearGradient>
                <circle cx="24" cy="24" r="20" fill="url(#ardn4qMWM6qJppYdTWAANa_wrIwUNhk1J4k_gr1)"></circle>
                <radialGradient id="ardn4qMWM6qJppYdTWAANb_wrIwUNhk1J4k_gr2" cx="24" cy="24" r="18.5" gradientUnits="userSpaceOnUse">
                    <stop offset="0"></stop>
                    <stop offset="1" stop-opacity="0"></stop>
                </radialGradient>
                <circle cx="24" cy="24" r="18.5" fill="url(#ardn4qMWM6qJppYdTWAANb_wrIwUNhk1J4k_gr2)"></circle>
                <radialGradient id="ardn4qMWM6qJppYdTWAANc_wrIwUNhk1J4k_gr3" cx="23.89" cy="7.394" r="37.883" gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#fafafb"></stop>
                    <stop offset="1" stop-color="#c8cdd1"></stop>
                </radialGradient>
                <circle cx="24" cy="24" r="17" fill="url(#ardn4qMWM6qJppYdTWAANc_wrIwUNhk1J4k_gr3)"></circle>
                <linearGradient id="ardn4qMWM6qJppYdTWAANd_wrIwUNhk1J4k_gr4" x1="22.479" x2="25.719" y1="9.361" y2="40.548" gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#c8cdd1"></stop>
                    <stop offset="1" stop-color="#a6aaad"></stop>
                </linearGradient>
                <path fill="url(#ardn4qMWM6qJppYdTWAANd_wrIwUNhk1J4k_gr4)" d="M25,10c0,0.552-0.448,1-1,1s-1-0.448-1-1c0-0.552,0.448-1,1-1S25,9.448,25,10z M24,37 c-0.552,0-1,0.448-1,1c0,0.552,0.448,1,1,1s1-0.448,1-1C25,37.448,24.552,37,24,37z M38,23c-0.552,0-1,0.448-1,1 c0,0.552,0.448,1,1,1s1-0.448,1-1C39,23.448,38.552,23,38,23z M10,23c-0.552,0-1,0.448-1,1c0,0.552,0.448,1,1,1s1-0.448,1-1 C11,23.448,10.552,23,10,23z"></path>
                <path fill="#d83b01" d="M24,34.75c-0.414,0-0.75-0.336-0.75-0.75V24c0-0.414,0.336-0.75,0.75-0.75s0.75,0.336,0.75,0.75v10 C24.75,34.414,24.414,34.75,24,34.75z"></path>
                <path fill="#45494d" d="M24,24.75c-0.192,0-0.384-0.073-0.53-0.22c-0.293-0.293-0.293-0.768,0-1.061l8.485-8.485 c0.293-0.293,0.768-0.293,1.061,0s0.293,0.768,0,1.061L24.53,24.53C24.384,24.677,24.192,24.75,24,24.75z"></path>
                <path fill="#45494d" d="M23.999,25.25c-0.181,0-0.365-0.039-0.54-0.123l-7.787-3.735c-0.623-0.299-0.885-1.045-0.586-1.668 c0.298-0.622,1.045-0.887,1.667-0.586l7.787,3.735c0.623,0.299,0.885,1.045,0.586,1.668C24.912,24.988,24.465,25.25,23.999,25.25z"></path>
                <circle cx="24" cy="24" r="2" fill="#1e2021"></circle>
            </svg>
            <span class="timer-text-simple" id="universal-timer-text">{{ $testDuration }} Minutes Left</span>
            <span class="timer-text-hover" id="universal-timer-text-hover">{{ $testDuration }}:00 Left</span>
        </div>
    </div>
@else
    {{-- Original floating timer --}}
    <div class="universal-timer-container timer-{{ $position }}" id="universal-timer-container">
        <div class="universal-timer-display" id="universal-timer-display">
            <div class="timer-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="timer-text" id="universal-timer-text">{{ $testDuration }}:00</div>
        </div>
        
        <div class="timer-progress" id="universal-timer-progress">
            <div class="timer-progress-bar" id="universal-timer-progress-bar"></div>
        </div>
    </div>
@endif

<style>
/* Integrated Timer Styles - Minimalist */
.timer-integrated {
    display: flex;
    align-items: center;
}

.timer-minimalist {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    position: relative;
    padding: 4px 0;
    transition: all 0.3s ease;
}

.timer-icon {
    width: 28px;
    height: 28px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    transition: all 0.3s ease;
}

.timer-minimalist:hover .timer-icon {
    transform: scale(1.15) rotate(10deg);
    filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.4));
}

.timer-text-simple {
    display: block;
    white-space: nowrap;
    text-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
}

.timer-text-hover {
    display: none;
    white-space: nowrap;
    text-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
}

/* Hover effect */
.timer-minimalist:hover .timer-text-simple {
    display: none;
}

.timer-minimalist:hover .timer-text-hover {
    display: block;
}

/* Warning state */
.timer-minimalist.warning {
    color: #fbbf24;
    animation: pulse-warning 2s infinite;
}

.timer-minimalist.warning .timer-icon {
    filter: drop-shadow(0 2px 6px rgba(245, 158, 11, 0.8));
}

/* Danger state */
.timer-minimalist.danger {
    color: #ef4444;
    animation: pulse-danger 1s infinite;
}

.timer-minimalist.danger .timer-icon {
    filter: drop-shadow(0 2px 8px rgba(239, 68, 68, 1));
    animation: shake 0.5s infinite;
}

/* Shake animation for danger */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-2px); }
    75% { transform: translateX(2px); }
}

/* Simple pulse animations */
@keyframes pulse-warning {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

@keyframes pulse-danger {
    0%, 100% { 
        opacity: 1;
        transform: scale(1);
    }
    50% { 
        opacity: 0.8;
        transform: scale(1.05);
    }
}

/* Original floating timer styles */
.universal-timer-container {
    position: fixed;
    z-index: 1000;
    user-select: none;
}

.timer-top-right {
    top: 20px;
    right: 20px;
}

.timer-top-left {
    top: 20px;
    left: 20px;
}

.timer-bottom-right {
    bottom: 20px;
    right: 20px;
}

.timer-bottom-left {
    bottom: 20px;
    left: 20px;
}

.universal-timer-display {
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    padding: 12px 16px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    min-width: 120px;
    justify-content: center;
}

.universal-timer-display.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    animation: pulse-warning 2s infinite;
}

.universal-timer-display.danger {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
    animation: pulse-danger 1s infinite;
}

.timer-progress {
    width: 100%;
    height: 4px;
    background-color: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
    margin-top: 8px;
    overflow: hidden;
}

.timer-progress-bar {
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 2px;
    transition: width 1s linear;
    width: 100%;
}

@keyframes pulse-warning {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes pulse-danger {
    0%, 100% { 
        transform: scale(1); 
        opacity: 1;
    }
    50% { 
        transform: scale(1.08); 
        opacity: 0.8;
    }
}

@media (max-width: 768px) {
    .timer-minimalist {
        font-size: 14px;
        gap: 8px;
    }
    
    .timer-icon {
        width: 24px;
        height: 24px;
    }
    
    /* On mobile, show time format directly */
    .timer-text-simple {
        display: none;
    }
    
    .timer-text-hover {
        display: block;
    }
    
    .universal-timer-container {
        top: 10px !important;
        right: 10px !important;
        left: auto !important;
        bottom: auto !important;
    }
    
    .universal-timer-display {
        padding: 10px 12px;
        font-size: 14px;
        min-width: 100px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.UniversalTimer) {
        console.log('Timer already initialized');
        return;
    }
    
    const config = {
        attemptStartTime: new Date('{{ $attemptStartTime->format('c') }}'),
        testDurationMinutes: {{ $testDuration }},
        warningTime: {{ $warningTime }},
        dangerTime: {{ $dangerTime }},
        autoSubmitFormId: '{{ $autoSubmitFormId }}',
        attemptId: {{ $attempt->id }},
        position: '{{ $position }}'
    };
    
    window.UniversalTimer = {
        config: config,
        testDurationMs: config.testDurationMinutes * 60 * 1000,
        timerInterval: null,
        isRunning: false,
        
        // DOM Elements - support both integrated and floating
        get timerDisplay() {
            return config.position === 'integrated' 
                ? document.getElementById('universal-timer-display-integrated')
                : document.getElementById('universal-timer-display');
        },
        
        get timerText() {
            return document.getElementById('universal-timer-text');
        },
        
        get progressBar() {
            return document.getElementById('universal-timer-progress-bar');
        },
        
        start: function() {
            if (this.isRunning) return;
            
            this.isRunning = true;
            this.updateTimer();
            this.timerInterval = setInterval(() => this.updateTimer(), 1000);
            this.setupNavigationPrevention();
            
            console.log('Universal Timer Started for attempt:', this.config.attemptId);
        },
        
        stop: function() {
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }
            this.isRunning = false;
            this.removeNavigationPrevention();
        },
        
        calculateRemainingTime: function() {
            const currentTime = new Date();
            const elapsedMs = currentTime.getTime() - this.config.attemptStartTime.getTime();
            const remainingMs = this.testDurationMs - elapsedMs;
            return Math.max(0, Math.floor(remainingMs / 1000));
        },
        
        updateTimer: function() {
            const remainingSeconds = this.calculateRemainingTime();
            
            if (remainingSeconds <= 0) {
                this.handleTimeUp();
                return;
            }
            
            this.updateDisplay(remainingSeconds);
            this.updateProgressBar(remainingSeconds);
            this.updateVisualState(remainingSeconds);
        },
        
        updateDisplay: function(remainingSeconds) {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (this.timerText) {
                // Update main text based on minutes
                if (minutes >= 1) {
                    this.timerText.textContent = `${minutes} Minute${minutes > 1 ? 's' : ''} Left`;
                } else {
                    this.timerText.textContent = `${seconds} Second${seconds !== 1 ? 's' : ''} Left`;
                }
            }
            
            // Update hover text
            const hoverText = document.getElementById('universal-timer-text-hover');
            if (hoverText) {
                hoverText.textContent = `${timeString} Left`;
            }
        },
        
        updateProgressBar: function(remainingSeconds) {
            const totalSeconds = this.config.testDurationMinutes * 60;
            const percentage = (remainingSeconds / totalSeconds) * 100;
            if (this.progressBar) {
                this.progressBar.style.width = `${percentage}%`;
            }
        },
        
        updateVisualState: function(remainingSeconds) {
            if (!this.timerDisplay) return;
            
            // For integrated timer, update the minimalist timer
            const minimalistTimer = document.querySelector('.timer-minimalist');
            if (minimalistTimer) {
                minimalistTimer.classList.remove('warning', 'danger');
                
                if (remainingSeconds <= this.config.dangerTime) {
                    minimalistTimer.classList.add('danger');
                } else if (remainingSeconds <= this.config.warningTime) {
                    minimalistTimer.classList.add('warning');
                }
            }
            
            // For floating timer, update as before
            this.timerDisplay.classList.remove('warning', 'danger');
            
            if (remainingSeconds <= this.config.dangerTime) {
                this.timerDisplay.classList.add('danger');
            } else if (remainingSeconds <= this.config.warningTime) {
                this.timerDisplay.classList.add('warning');
            }
        },
        
        handleTimeUp: function() {
            this.stop();
            console.log('Time is up! Auto-submitting...');
            
            this.saveCurrentState();
            
            if (this.config.autoSubmitFormId) {
                const form = document.getElementById(this.config.autoSubmitFormId);
                if (form) {
                    this.removeNavigationPrevention();
                    form.submit();
                    return;
                }
            }
            
            this.showTimeUpNotification();
        },
        
        saveCurrentState: function() {
            try {
                const forms = document.querySelectorAll('form');
                if (forms.length > 0) {
                    const formData = new FormData(forms[0]);
                    const data = {};
                    
                    for (let [key, value] of formData.entries()) {
                        data[key] = value;
                    }
                    
                    const textareas = document.querySelectorAll('textarea');
                    textareas.forEach((textarea) => {
                        if (textarea.name) {
                            data[textarea.name] = textarea.value;
                        }
                    });
                    
                    const radios = document.querySelectorAll('input[type="radio"]:checked');
                    radios.forEach((radio) => {
                        data[radio.name] = radio.value;
                    });
                    
                    localStorage.setItem(`testBackup_${this.config.attemptId}`, JSON.stringify({
                        data: data,
                        timestamp: Date.now(),
                        timeUp: true
                    }));
                }
            } catch (error) {
                console.error('Error saving test data:', error);
            }
        },
        
        setupNavigationPrevention: function() {
            this.beforeUnloadHandler = (e) => {
                if (this.isRunning) {
                    e.preventDefault();
                    e.returnValue = 'Your test is in progress. Are you sure you want to leave?';
                    return 'Your test is in progress. Are you sure you want to leave?';
                }
            };
            
            window.addEventListener('beforeunload', this.beforeUnloadHandler);
            
            history.pushState(null, null, location.href);
            this.popstateHandler = (e) => {
                if (this.isRunning) {
                    history.pushState(null, null, location.href);
                    alert('Please use the Submit button to finish your test properly.');
                }
            };
            window.addEventListener('popstate', this.popstateHandler);
        },
        
        removeNavigationPrevention: function() {
            if (this.beforeUnloadHandler) {
                window.removeEventListener('beforeunload', this.beforeUnloadHandler);
            }
            if (this.popstateHandler) {
                window.removeEventListener('popstate', this.popstateHandler);
            }
        },
        
        showTimeUpNotification: function() {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                    <div style="background: white; padding: 2rem; border-radius: 1rem; text-align: center; max-width: 400px;">
                        <h2 style="color: #dc2626; font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Time's Up!</h2>
                        <p style="margin-bottom: 1.5rem;">Your test time has expired. Please submit your test.</p>
                        <button onclick="this.parentElement.parentElement.remove()" style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.5rem; cursor: pointer;">OK</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        },
        
        getRemainingTime: function() {
            return this.calculateRemainingTime();
        },
        
        getElapsedTime: function() {
            const currentTime = new Date();
            const elapsedMs = currentTime.getTime() - this.config.attemptStartTime.getTime();
            return Math.floor(elapsedMs / 1000);
        },
        
        pause: function() {
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }
        },
        
        resume: function() {
            if (!this.timerInterval && this.isRunning) {
                this.timerInterval = setInterval(() => this.updateTimer(), 1000);
            }
        }
    };
    
    window.UniversalTimer.start();
});

window.getTimerStatus = function() {
    if (window.UniversalTimer) {
        return {
            remaining: window.UniversalTimer.getRemainingTime(),
            elapsed: window.UniversalTimer.getElapsedTime(),
            isRunning: window.UniversalTimer.isRunning
        };
    }
    return null;
};

window.pauseTimer = function() {
    if (window.UniversalTimer) {
        window.UniversalTimer.pause();
    }
};

window.resumeTimer = function() {
    if (window.UniversalTimer) {
        window.UniversalTimer.resume();
    }
};
</script>