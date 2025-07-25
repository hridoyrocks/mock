<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'IELTS Mock Platform' }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        
        /* Remove default styling */
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
        
        /* Mobile styles */
        @media screen and (max-width: 600px) {
            .mobile-hide { display: none !important; }
            .mobile-center { text-align: center !important; }
            .container { padding: 0 !important; }
            .content { padding: 0 20px !important; }
            .button { display: block !important; width: 100% !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #FFF5F5; color: #2D3748;">
    
    <!-- Wrapper Table -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FFF5F5; min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                
                <!-- Container -->
                <table class="container" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 0 0 40px 0;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <!-- Logo -->
                                        @php
                                            $websiteSetting = \App\Models\WebsiteSetting::first();
                                            $logoUrl = $websiteSetting && $websiteSetting->logo_url ? $websiteSetting->logo_url : null;
                                        @endphp
                                        
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" alt="IELTS Mock Platform" style="height: 60px; max-width: 200px; margin-bottom: 16px;">
                                        @else
                                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); border-radius: 16px; display: inline-block; position: relative; margin-bottom: 16px;">
                                                <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 24px; font-weight: bold;">IM</span>
                                            </div>
                                        @endif
                                        
                                        <h1 style="margin: 0; color: #DC2626; font-size: 24px; font-weight: 700;">IELTS Mock Platform</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td>
                            <table class="content" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                                
                                <!-- Red Accent Bar -->
                                <tr>
                                    <td style="background: linear-gradient(90deg, #DC2626 0%, #EF4444 100%); height: 4px;"></td>
                                </tr>
                                
                                <!-- Email Content -->
                                <tr>
                                    <td style="padding: 40px 40px 32px 40px;">
                                        @yield('content')
                                    </td>
                                </tr>
                                
                                <!-- Call to Action Section -->
                                @hasSection('action')
                                <tr>
                                    <td style="padding: 0 40px 40px 40px;">
                                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <tr>
                                                <td align="center" style="padding: 24px; background-color: #FEF2F2; border-radius: 12px;">
                                                    @yield('action')
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                                
                                <!-- Additional Info Section -->
                                @hasSection('additional')
                                <tr>
                                    <td style="padding: 0 40px 40px 40px;">
                                        <table border="0" cellspacing="0" cellpadding="0" width="100%" style="border-top: 1px solid #FEE2E2; padding-top: 24px;">
                                            <tr>
                                                <td>
                                                    @yield('additional')
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                                
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 32px 20px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <!-- Social Links -->
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: #FEE2E2; border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                                        <img src="https://img.icons8.com/ios-filled/24/DC2626/facebook-new.png" alt="Facebook" width="20" height="20" style="vertical-align: middle;">
                                                    </a>
                                                </td>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: #FEE2E2; border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                                        <img src="https://img.icons8.com/ios-filled/24/DC2626/twitter.png" alt="Twitter" width="20" height="20" style="vertical-align: middle;">
                                                    </a>
                                                </td>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: #FEE2E2; border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                                        <img src="https://img.icons8.com/ios-filled/24/DC2626/linkedin.png" alt="LinkedIn" width="20" height="20" style="vertical-align: middle;">
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <!-- Footer Text -->
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0 0 8px 0; color: #9CA3AF; font-size: 14px;">
                                            © {{ date('Y') }} CD IELTS. All rights reserved.
                                        </p>
                                        <p style="margin: 0 0 8px 0; color: #9CA3AF; font-size: 14px;">
                                            You're receiving this email because you're registered with CD IELTS.
                                        </p>
                                        <p style="margin: 0; color: #9CA3AF; font-size: 14px;">
                                            <a href="{{ url('/profile') }}" style="color: #DC2626; text-decoration: none;">Manage Preferences</a> • 
                                            <a href="{{ url('/unsubscribe') }}" style="color: #DC2626; text-decoration: none;">Unsubscribe</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>
