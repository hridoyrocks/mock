@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-6 max-w-4xl">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-red-500">Home</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900 font-medium">Privacy Policy</span></li>
            </ol>
        </nav>
        
        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Privacy Policy</h1>
            <p class="text-gray-600 mb-8">Last updated: {{ date('F d, Y') }}</p>
            
            <!-- Introduction -->
            <section class="mb-8">
                <p class="text-gray-700 leading-relaxed mb-4">
                    CD IELTS ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our IELTS preparation platform.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the site.
                </p>
            </section>
            
            <!-- Information We Collect -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Information We Collect</h2>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Personal Information</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We collect information you provide directly to us, such as:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4 mb-4">
                    <li>Name and email address</li>
                    <li>Phone number</li>
                    <li>Password and account credentials</li>
                    <li>Payment information</li>
                    <li>Test responses and scores</li>
                    <li>Profile information</li>
                </ul>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Automatically Collected Information</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                    When you visit our platform, we automatically collect:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li>IP address and device information</li>
                    <li>Browser type and version</li>
                    <li>Usage data and analytics</li>
                    <li>Test performance metrics</li>
                    <li>Session duration and activity</li>
                </ul>
            </section>
            
            <!-- How We Use Your Information -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. How We Use Your Information</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We use the information we collect to:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li>Provide and maintain our services</li>
                    <li>Process your payments and subscriptions</li>
                    <li>Send you test results and feedback</li>
                    <li>Improve our platform and develop new features</li>
                    <li>Communicate with you about updates and offers</li>
                    <li>Monitor and analyze usage patterns</li>
                    <li>Prevent fraud and ensure security</li>
                    <li>Comply with legal obligations</li>
                </ul>
            </section>
            
            <!-- Data Sharing -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Information Sharing</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We do not sell, trade, or rent your personal information. We may share your information only in the following situations:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li>With your consent</li>
                    <li>With service providers who assist our operations</li>
                    <li>For legal compliance and law enforcement</li>
                    <li>To protect our rights and safety</li>
                    <li>In connection with a business transaction</li>
                </ul>
            </section>
            
            <!-- Data Security -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Data Security</h2>
                <p class="text-gray-700 leading-relaxed">
                    We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.
                </p>
            </section>
            
            <!-- Your Rights -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Your Rights</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    You have the right to:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li>Access your personal information</li>
                    <li>Correct inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Export your data</li>
                </ul>
            </section>
            
            <!-- Cookies -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Cookies</h2>
                <p class="text-gray-700 leading-relaxed">
                    We use cookies and similar tracking technologies to track activity on our platform and hold certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.
                </p>
            </section>
            
            <!-- Children's Privacy -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Children's Privacy</h2>
                <p class="text-gray-700 leading-relaxed">
                    Our services are not intended for individuals under the age of 13. We do not knowingly collect personal information from children under 13.
                </p>
            </section>
            
            <!-- Changes to Policy -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Changes to This Policy</h2>
                <p class="text-gray-700 leading-relaxed">
                    We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.
                </p>
            </section>
            
            <!-- Contact -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Contact Us</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    If you have any questions about this Privacy Policy, please contact us:
                </p>
                <ul class="text-gray-700 space-y-2">
                    <li><strong>Email:</strong> privacy@cdielts.com</li>
                    <li><strong>Phone:</strong> +880 1234-567890</li>
                    <li><strong>Address:</strong> House 12, Road 5, Block A, Mirpur, Dhaka 1216, Bangladesh</li>
                </ul>
            </section>
        </div>
    </div>
</div>
@endsection