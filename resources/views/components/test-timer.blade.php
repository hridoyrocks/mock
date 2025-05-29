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
        <div class="flex items-center ml-4">
            <svg class="w-4 h-4 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="timer-display-integrated" id="universal-timer-display-integrated">
                <span class="timer-text-integrated text-white font-medium" id="universal-timer-text">{{ $testDuration }}:00</span>
            </div>
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
/* Integrated Timer Styles */
.timer-integrated {
    display: flex;
    align-items: center;
}

.timer-display-integrated {
    padding: 6px 12px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    min-width: 70px;
    text-align: center;
}

.timer-display-integrated.warning {
    background: rgba(245, 158, 11, 0.9);
    border-color: rgba(245, 158, 11, 1);
    animation: pulse-warning 2s infinite;
}

.timer-display-integrated.danger {
    background: rgba(220, 38, 38, 0.9);
    border-color: rgba(220, 38, 38, 1);
    animation: pulse-danger 1s infinite;
}

.timer-text-integrated {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.5px;
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
    .timer-integrated {
        margin-left: 8px;
    }
    
    .timer-display-integrated {
        padding: 4px 8px;
        min-width: 60px;
    }
    
    .timer-text-integrated {
        font-size: 12px;
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
            
            if (this.timerText) {
                this.timerText.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
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