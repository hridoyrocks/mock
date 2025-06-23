<div class="grid grid-cols-2 gap-4">
    <!-- Question Type -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
        <select id="question_type" name="question_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            <option value="">Select type...</option>
            @foreach($questionTypes as $key => $type)
                <option value="{{ $key }}">{{ $type }}</option>
            @endforeach
        </select>
    </div>
    
    <!-- Question Number -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Number <span class="text-red-500">*</span></label>
        <input type="number" name="order_number" value="{{ old('order_number', $nextQuestionNumber) }}" 
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" required>
    </div>
    
    <!-- Part/Task Selection -->
    @if(in_array($testSet->section->name, ['listening', 'reading', 'speaking', 'writing']))
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $testSet->section->name === 'writing' ? 'Task' : 'Part' }} <span class="text-red-500">*</span>
        </label>
        <select name="part_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            @if($testSet->section->name === 'listening')
                <option value="1">Part 1 (Social)</option>
                <option value="2">Part 2 (Monologue)</option>
                <option value="3">Part 3 (Discussion)</option>
                <option value="4">Part 4 (Lecture)</option>
            @elseif($testSet->section->name === 'reading')
                <option value="1">Passage 1</option>
                <option value="2">Passage 2</option>
                <option value="3">Passage 3</option>
            @elseif($testSet->section->name === 'speaking')
                <option value="1">Part 1 (Introduction)</option>
                <option value="2">Part 2 (Cue Card)</option>
                <option value="3">Part 3 (Discussion)</option>
            @elseif($testSet->section->name === 'writing')
                <option value="1">Task 1</option>
                <option value="2">Task 2</option>
            @endif
        </select>
    </div>
    @endif
    
    <!-- Marks -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Marks</label>
        <input type="number" name="marks" value="{{ old('marks', 1) }}" 
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
               min="0" max="40">
    </div>
    
    <!-- Question Group -->
    <div class="col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Group</label>
        <input type="text" name="question_group" placeholder="e.g., Questions 1-5"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
</div>