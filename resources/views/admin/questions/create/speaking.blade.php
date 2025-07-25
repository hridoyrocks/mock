<x-layout>
    <x-slot:title>Add Question - Speaking</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold">Add Speaking Question</h1>
                        <p class="text-orange-100 text-sm mt-1">{{ $testSet->title }}</p>
                    </div>
                    <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                       class="inline-flex items-center px-3 sm:px-4 py-2 bg-white/10 backdrop-blur border border-white/20 text-white text-sm font-medium rounded-md hover:bg-white/20 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
            
            @include('admin.questions.partials.question-header')
            
            <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" id="questionForm">
                @csrf
                <input type="hidden" name="test_set_id" value="{{ $testSet->id }}">
                
                <div class="space-y-4 sm:space-y-6">
                    <!-- Speaking Question -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Speaking Question</h3>
                        </div>
                        
                        <div class="p-4 sm:p-6 space-y-6">
                            <!-- Question Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Question Type <span class="text-red-500">*</span>
                                </label>
                                <select id="question_type" name="question_type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 text-sm" required>
                                    <option value="">Select type...</option>
                                    <option value="part1_personal">Part 1: Personal Questions</option>
                                    <option value="part2_cue_card">Part 2: Cue Card</option>
                                    <option value="part3_discussion">Part 3: Discussion</option>
                                </select>
                            </div>
                            
                            <!-- Question Content -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Question / Topic <span class="text-red-500">*</span>
                                </label>
                                <textarea id="content" name="content" class="tinymce-editor">{{ old('content') }}</textarea>
                            </div>
                            
                            <!-- Cue Card Points (Part 2 only) -->
                            <div id="cue-card-section" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Cue Card Points <span class="text-sm font-normal text-gray-500">(One per line)</span>
                                </label>
                                <textarea id="cue-card-points" name="cue_card_points_text" rows="5" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 text-sm"
                                          placeholder="Where you went&#10;When you went there&#10;Who you went with&#10;What you did there&#10;And explain why it was memorable">{{ old('cue_card_points_text') }}</textarea>
                                <input type="hidden" id="form_structure_json" name="form_structure_json">
                            </div>
                            
                            <!-- Basic Settings Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Question Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Question Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="order_number" value="{{ old('order_number', $nextQuestionNumber ?? 1) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 text-sm" 
                                           min="1" required>
                                </div>
                                
                                <!-- Speaking Tips (Optional) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Quick Tip <span class="text-sm font-normal text-gray-500">(Optional)</span>
                                    </label>
                                    <input type="text" name="speaking_tips" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 text-sm"
                                           placeholder="e.g., Give specific examples"
                                           value="{{ old('speaking_tips') }}">
                                </div>
                            </div>
                            
                            <!-- Hidden Fields with Auto Values -->
                            <input type="hidden" name="part_number" id="part_number" value="{{ old('part_number', 1) }}">
                            <input type="hidden" name="time_limit" id="time_limit" value="{{ old('time_limit', 2) }}">
                            <input type="hidden" name="marks" value="1">
                            
                            <!-- Progressive Card Settings (Hidden with defaults) -->
                            <input type="hidden" name="read_time" id="read_time" value="{{ old('read_time', 5) }}">
                            <input type="hidden" name="min_response_time" id="min_response_time" value="{{ old('min_response_time', 15) }}">
                            <input type="hidden" name="max_response_time" id="max_response_time" value="{{ old('max_response_time', 45) }}">
                            <input type="hidden" name="auto_progress" id="auto_progress" value="{{ old('auto_progress', 1) }}">
                            <input type="hidden" name="card_theme" id="card_theme" value="{{ old('card_theme', 'blue') }}">
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" name="action" value="save" class="flex-1 py-2.5 sm:py-3 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700 transition-colors text-sm sm:text-base">
                                Save Question
                            </button>
                            <button type="submit" name="action" value="save_and_new" class="flex-1 py-2.5 sm:py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors text-sm sm:text-base">
                                Save & Add Another
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.questions.partials.modals')
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script src="{{ asset('js/admin/question-speaking.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize TinyMCE for main content
        tinymce.init({
            selector: '.tinymce-editor',
            height: 250,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap',
                'preview', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | fontsize | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | removeformat code',
            font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
            content_css: '//www.tiny.cloud/css/codepen.min.css',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
        
        const questionType = document.getElementById('question_type');
        const cueCardSection = document.getElementById('cue-card-section');
        
        // Auto settings based on question type
        const typeSettings = {
            'part1_personal': {
                part: 1,
                time_limit: 1,
                read_time: 5,
                min_response: 15,
                max_response: 45,
                auto_progress: 1,
                theme: 'blue'
            },
            'part2_cue_card': {
                part: 2,
                time_limit: 2,
                read_time: 60,
                min_response: 60,
                max_response: 120,
                auto_progress: 0,
                theme: 'purple'
            },
            'part3_discussion': {
                part: 3,
                time_limit: 5,
                read_time: 8,
                min_response: 30,
                max_response: 90,
                auto_progress: 1,
                theme: 'green'
            }
        };

        // Update settings when question type changes
        questionType.addEventListener('change', function() {
            const selectedType = this.value;
            
            // Show/hide cue card section
            if (selectedType === 'part2_cue_card') {
                cueCardSection.classList.remove('hidden');
            } else {
                cueCardSection.classList.add('hidden');
            }
            
            // Auto-fill hidden fields
            if (typeSettings[selectedType]) {
                const settings = typeSettings[selectedType];
                document.getElementById('part_number').value = settings.part;
                document.getElementById('time_limit').value = settings.time_limit;
                document.getElementById('read_time').value = settings.read_time;
                document.getElementById('min_response_time').value = settings.min_response;
                document.getElementById('max_response_time').value = settings.max_response;
                document.getElementById('auto_progress').value = settings.auto_progress;
                document.getElementById('card_theme').value = settings.theme;
            }
        });

        // Convert cue card points to JSON
        function updateCueCardStructure() {
            const pointsText = document.getElementById('cue-card-points').value;
            if (pointsText) {
                const points = pointsText.split('\n').filter(p => p.trim());
                const formStructure = {
                    fields: points.map(point => ({ label: point.trim() }))
                };
                document.getElementById('form_structure_json').value = JSON.stringify(formStructure);
            }
        }

        // Update structure on input
        document.getElementById('cue-card-points').addEventListener('input', updateCueCardStructure);
        
        // Ensure structure is updated on form submit
        document.getElementById('questionForm').addEventListener('submit', function(e) {
            if (questionType.value === 'part2_cue_card') {
                updateCueCardStructure();
            }
        });
    });
    </script>
    @endpush
</x-layout>