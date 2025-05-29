<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <style>
        .section-card {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }
        
        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .sortable-ghost {
            opacity: 0.4;
            background: #e2e8f0;
        }
        
        .sortable-chosen {
            transform: scale(1.02);
            box-shadow: 0 8px 25px -3px rgba(0, 0, 0, 0.15);
        }
        
        .drag-handle {
            cursor: move;
            opacity: 0.5;
            transition: opacity 0.2s;
        }
        
        .drag-handle:hover {
            opacity: 1;
        }
        
        .question-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .question-item:hover {
            border-color: #3b82f6;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
        }
        
        .sidebar-menu {
            background: linear-gradient(145deg, #1e293b, #334155);
        }
        
        .menu-item {
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 4px 0;
        }
        
        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        
        .menu-item.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }
        
        .content-section {
            display: none;
        }
        
        .content-section.active {
            display: block;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }
        
        .floating-button {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 50;
        }
        
        .floating-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .listening-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .reading-gradient { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .writing-gradient { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .speaking-gradient { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="fixed left-0 top-0 h-full w-64 sidebar-menu text-white p-6 z-40">
        <div class="mb-8">
            <h1 class="text-2xl font-bold">IELTS Admin</h1>
            <p class="text-sm opacity-70">Question Management</p>
        </div>
        
        <nav class="space-y-2">
            <div class="menu-item active px-4 py-3 cursor-pointer" data-section="dashboard">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </div>
            <div class="menu-item px-4 py-3 cursor-pointer" data-section="listening">
                <i class="fas fa-headphones mr-3"></i>
                Listening Section
            </div>
            <div class="menu-item px-4 py-3 cursor-pointer" data-section="reading">
                <i class="fas fa-book-open mr-3"></i>
                Reading Section
            </div>
            <div class="menu-item px-4 py-3 cursor-pointer" data-section="writing">
                <i class="fas fa-pen mr-3"></i>
                Writing Section
            </div>
            <div class="menu-item px-4 py-3 cursor-pointer" data-section="speaking">
                <i class="fas fa-microphone mr-3"></i>
                Speaking Section
            </div>
            <hr class="my-4 border-gray-600">
            <div class="menu-item px-4 py-3 cursor-pointer" data-section="settings">
                <i class="fas fa-cog mr-3"></i>
                Settings
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <!-- Dashboard Section -->
        <div id="dashboard" class="content-section active">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Overview</h2>
                <p class="text-gray-600">Manage your IELTS test sections and questions</p>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stats-card listening-gradient">
                    <div class="relative z-10">
                        <h3 class="text-lg font-semibold mb-2">Listening</h3>
                        <p class="text-3xl font-bold">24</p>
                        <p class="text-sm opacity-80">Questions</p>
                    </div>
                </div>
                <div class="stats-card reading-gradient">
                    <div class="relative z-10">
                        <h3 class="text-lg font-semibold mb-2">Reading</h3>
                        <p class="text-3xl font-bold">36</p>
                        <p class="text-sm opacity-80">Questions</p>
                    </div>
                </div>
                <div class="stats-card writing-gradient">
                    <div class="relative z-10">
                        <h3 class="text-lg font-semibold mb-2">Writing</h3>
                        <p class="text-3xl font-bold">8</p>
                        <p class="text-sm opacity-80">Tasks</p>
                    </div>
                </div>
                <div class="stats-card speaking-gradient">
                    <div class="relative z-10">
                        <h3 class="text-lg font-semibold mb-2">Speaking</h3>
                        <p class="text-3xl font-bold">12</p>
                        <p class="text-sm opacity-80">Cue Cards</p>
                    </div>
                </div>
            </div>
            
            <!-- Section Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="section-card rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-headphones text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Listening Section</h3>
                            <p class="text-gray-600">Manage audio-based questions</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-700">24 Questions</span>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors" data-section="listening">
                            Manage
                        </button>
                    </div>
                </div>
                
                <div class="section-card rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-book-open text-pink-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Reading Section</h3>
                            <p class="text-gray-600">Manage passages and comprehension</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-700">36 Questions</span>
                        <button class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition-colors" data-section="reading">
                            Manage
                        </button>
                    </div>
                </div>
                
                <div class="section-card rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-pen text-cyan-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Writing Section</h3>
                            <p class="text-gray-600">Manage writing tasks and prompts</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-centers">
                        <span class="text-2xl font-bold text-gray-700">8 Tasks</span>
                        <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700 transition-colors" data-section="writing">
                            Manage
                        </button>
                    </div>
                </div>
                
                <div class="section-card rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-microphone text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Speaking Section</h3>
                            <p class="text-gray-600">Manage cue cards and topics</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-700">12 Cue Cards</span>
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors" data-section="speaking">
                            Manage
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listening Section -->
        <div id="listening" class="content-section">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Listening Section</h2>
                    <p class="text-gray-600">Manage audio-based questions and test sets</p>
                </div>
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center" onclick="openModal('listening')">
                    <i class="fas fa-plus mr-2"></i>
                    Add Question
                </button>
            </div>
            
            <!-- Test Sets -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4">Test Sets</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="listening-testsets">
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <h4 class="font-semibold mb-2">Listening Test 1</h4>
                        <p class="text-gray-600 text-sm mb-3">Academic Module</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600">8 Questions</span>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Questions List -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Questions</h3>
                    <p class="text-gray-600 text-sm">Drag and drop to reorder questions</p>
                </div>
                <div id="listening-questions" class="p-6">
                    <div class="question-item flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-grip-vertical drag-handle mr-4 text-gray-400"></i>
                            <div>
                                <h4 class="font-semibold">Question 1: Multiple Choice</h4>
                                <p class="text-gray-600 text-sm">What is the main topic of the conversation?</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Audio</span>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="question-item flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-grip-vertical drag-handle mr-4 text-gray-400"></i>
                            <div>
                                <h4 class="font-semibold">Question 2: Fill in the Blanks</h4>
                                <p class="text-gray-600 text-sm">Complete the missing information</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Text</span>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reading Section -->
        <div id="reading" class="content-section">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Reading Section</h2>
                    <p class="text-gray-600">Manage reading passages and comprehension questions</p>
                </div>
                <button class="bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 transition-colors flex items-center" onclick="openModal('reading')">
                    <i class="fas fa-plus mr-2"></i>
                    Add Question
                </button>
            </div>
            
            <!-- Passages -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4">Reading Passages</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-6 border border-gray-200">
                        <h4 class="font-semibold mb-2">Passage 1: Climate Change</h4>
                        <p class="text-gray-600 text-sm mb-4">Academic reading about environmental impacts...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-pink-600">12 Questions</span>
                            <div class="space-x-2">
                                <button class="text-pink-600 hover:text-pink-800">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Questions List -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Questions</h3>
                </div>
                <div id="reading-questions" class="p-6">
                    <div class="question-item flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-grip-vertical drag-handle mr-4 text-gray-400"></i>
                            <div>
                                <h4 class="font-semibold">Question 1: True/False/Not Given</h4>
                                <p class="text-gray-600 text-sm">The author supports renewable energy...</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">T/F/NG</span>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Writing Section -->
        <div id="writing" class="content-section">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Writing Section</h2>
                    <p class="text-gray-600">Manage writing tasks and essay prompts</p>
                </div>
                <button class="bg-cyan-600 text-white px-6 py-3 rounded-lg hover:bg-cyan-700 transition-colors flex items-center" onclick="openModal('writing')">
                    <i class="fas fa-plus mr-2"></i>
                    Add Task
                </button>
            </div>
            
            <!-- Writing Tasks -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <span class="px-3 py-1 bg-cyan-100 text-cyan-800 text-sm rounded-full mr-3">Task 1</span>
                        <h4 class="font-semibold">Graph Description</h4>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Describe the chart showing population growth...</p>
                    <div class="flex justify-between items-center">
                        <span class="text-cyan-600">150+ words</span>
                        <div class="space-x-2">
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full mr-3">Task 2</span>
                        <h4 class="font-semibold">Essay Writing</h4>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Discuss the advantages and disadvantages of...</p>
                    <div class="flex justify-between items-center">
                        <span class="text-purple-600">250+ words</span>
                        <div class="space-x-2">
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Speaking Section -->
        <div id="speaking" class="content-section">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Speaking Section</h2>
                    <p class="text-gray-600">Manage cue cards and speaking topics</p>
                </div>
                <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center" onclick="openModal('speaking')">
                    <i class="fas fa-plus mr-2"></i>
                    Add Cue Card
                </button>
            </div>
            
            <!-- Speaking Parts -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <h4 class="font-semibold mb-2">Part 1: Introduction</h4>
                    <p class="text-gray-600 text-sm mb-4">Personal questions and familiar topics</p>
                    <div class="flex justify-between items-center">
                        <span class="text-green-600">4 Topics</span>
                        <button class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <h4 class="font-semibold mb-2">Part 2: Cue Cards</h4>
                    <p class="text-gray-600 text-sm mb-4">Individual long turn presentations</p>
                    <div class="flex justify-between items-center">
                        <span class="text-green-600">6 Cards</span>
                        <button class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <h4 class="font-semibold mb-2">Part 3: Discussion</h4>
                    <p class="text-gray-600 text-sm mb-4">Abstract ideas and issues</p>
                    <div class="flex justify-between items-center">
                        <span class="text-green-600">8 Topics</span>
                        <button class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Cue Cards -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Cue Cards</h3>
                </div>
                <div id="speaking-questions" class="p-6">
                    <div class="question-item flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-grip-vertical drag-handle mr-4 text-gray-400"></i>
                            <div>
                                <h4 class="font-semibold">Describe a memorable journey</h4>
                                <p class="text-gray-600 text-sm">Talk about a journey that was memorable to you...</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Part 2</span>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Section -->
        <div id="settings" class="content-section">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Settings</h2>
                <p class="text-gray-600">Configure your IELTS test platform</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">General Settings</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium">Auto-save questions</h4>
                            <p class="text-gray-600 text-sm">Automatically save question changes</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Add Button -->
    <div class="floating-button" onclick="openModal('general')">
        <i class="fas fa-plus text-xl"></i>
    </div>

    <!-- Modal -->
    <div id="questionModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Add New Question</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Question Type</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>Multiple Choice</option>
                        <option>True/False/Not Given</option>
                        <option>Fill in the Blanks</option>
                        <option>Short Answer</option>
                        <option>Essay</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Question Content</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="4" placeholder="Enter your question here..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Media Upload</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600">Drag and drop files here, or click to browse</p>
                        <input type="file" class="hidden" id="fileUpload">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Save Question
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Navigation functionality
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                const section = this.dataset.section;
                
                // Update active menu item
                document.querySelectorAll('.menu-item').forEach(menu => menu.classList.remove('active'));
                this.classList.add('active');
                
                // Show corresponding content section
                document.querySelectorAll('.content-section').forEach(content => content.classList.remove('active'));
                document.getElementById(section).classList.add('active');
            });
        });

        // Section card click handlers
        document.querySelectorAll('[data-section]').forEach(button => {
            if (button.tagName === 'BUTTON') {
                button.addEventListener('click', function() {
                    const section = this.dataset.section;
                    
                    // Update active menu item
                    document.querySelectorAll('.menu-item').forEach(menu => menu.classList.remove('active'));
                    document.querySelector(`[data-section="${section}"].menu-item`).classList.add('active');
                    
                    // Show corresponding content section
                    document.querySelectorAll('.content-section').forEach(content => content.classList.remove('active'));
                    document.getElementById(section).classList.add('active');
                });
            }
        });

        // Initialize drag and drop for all question lists
        ['listening-questions', 'reading-questions', 'speaking-questions'].forEach(listId => {
            const element = document.getElementById(listId);
            if (element) {
                new Sortable(element, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function(evt) {
                        console.log(`Question moved from ${evt.oldIndex} to ${evt.newIndex}`);
                        // Here you would send an AJAX request to update the order in the database
                    }
                });
            }
        });

        // Modal functionality
        function openModal(section) {
            document.getElementById('questionModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('questionModal').classList.remove('active');
        }

        // File upload functionality
        document.addEventListener('DOMContentLoaded', function() {
            const fileUpload = document.getElementById('fileUpload');
            const uploadArea = fileUpload.parentElement;
            
            uploadArea.addEventListener('click', () => fileUpload.click());
            
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('border-blue-500', 'bg-blue-50');
            });
            
            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
            });
            
            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
                const files = e.dataTransfer.files;
                handleFiles(files);
            });
            
            fileUpload.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });
        });

        function handleFiles(files) {
            for (let file of files) {
                console.log('File uploaded:', file.name);
                // Handle file upload logic here
            }
        }

        // Auto-save functionality simulation
        let autoSaveTimeout;
        document.querySelectorAll('textarea, input').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    console.log('Auto-saving...');
                    // Implement auto-save logic here
                }, 2000);
            });
        });
    </script>
</body>
</html>