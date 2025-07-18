<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Mock Platform - Installation</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">IELTS Mock Platform</h1>
                <p class="mt-2 text-gray-600">Installation Wizard</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="@yield('step1', 'bg-gray-300') rounded-full h-8 w-8 flex items-center justify-center text-white text-sm">1</div>
                        <span class="ml-2 text-sm text-gray-600">Welcome</span>
                    </div>
                    <div class="flex items-center">
                        <div class="@yield('step2', 'bg-gray-300') rounded-full h-8 w-8 flex items-center justify-center text-white text-sm">2</div>
                        <span class="ml-2 text-sm text-gray-600">Requirements</span>
                    </div>
                    <div class="flex items-center">
                        <div class="@yield('step3', 'bg-gray-300') rounded-full h-8 w-8 flex items-center justify-center text-white text-sm">3</div>
                        <span class="ml-2 text-sm text-gray-600">Permissions</span>
                    </div>
                    <div class="flex items-center">
                        <div class="@yield('step4', 'bg-gray-300') rounded-full h-8 w-8 flex items-center justify-center text-white text-sm">4</div>
                        <span class="ml-2 text-sm text-gray-600">Database</span>
                    </div>
                    <div class="flex items-center">
                        <div class="@yield('step5', 'bg-gray-300') rounded-full h-8 w-8 flex items-center justify-center text-white text-sm">5</div>
                        <span class="ml-2 text-sm text-gray-600">Admin</span>
                    </div>
                    <div class="flex items-center">
                        <div class="@yield('step6', 'bg-gray-300') rounded-full h-8 w-8 flex items-center justify-center text-white text-sm">6</div>
                        <span class="ml-2 text-sm text-gray-600">Complete</span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
