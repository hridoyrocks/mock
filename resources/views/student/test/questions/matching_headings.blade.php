@if($question->question_type === 'matching_headings')
    @php
        $matchingData = $question->getMatchingHeadingsData();
        $headings = $matchingData['headings'] ?? [];
        $mappings = $matchingData['mappings'] ?? [];
    @endphp
    
    <!-- Instructions and Headings List (Show once) -->
    <div class="matching-headings-section mb-8">
        <!-- Instructions -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <p class="text-gray-700">{{ $question->instructions ?? 'Choose the correct heading for each paragraph from the list of headings below.' }}</p>
        </div>
        
        <!-- List of Headings -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">List of Headings</h3>
            <div class="space-y-2">
                @foreach($question->options as $index => $option)
                    <div class="flex items-start">
                        <span class="font-semibold mr-3 text-gray-600">{{ chr(65 + $index) }}.</span>
                        <span class="text-gray-700">{{ $option->content }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Individual Questions -->
        <div class="space-y-4">
            @foreach($mappings as $mapping)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold mr-3">
                                {{ $mapping['question'] }}
                            </span>
                            <span class="text-gray-700 font-medium">
                                Paragraph {{ $mapping['paragraph'] }}
                            </span>
                        </div>
                        
                        <!-- Dropdown for Answer -->
                        <select name="answers[{{ $mapping['question'] }}]" 
                                class="form-select rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="">Select heading...</option>
                            @foreach($question->options as $index => $option)
                                <option value="{{ chr(65 + $index) }}">
                                    {{ chr(65 + $index) }}. {{ Str::limit($option->content, 50) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
