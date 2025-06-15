<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
        $settings = App\Models\SiteSetting::first();
        @endphp
        <link rel="icon" href="{{ asset($settings?->favicon) }}" type="image/x-icon">
        <title>{{ config('app.name', 'DITS') }} - @yield('title', 'Website') </title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        <!--Start of Tawk.to Script-->
<!-- <script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/681013103aab2b190ea2795c/1ipvckur1';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script> -->

<a href="https://wa.me/8801829022555" target="_blank"
   style="position: fixed; bottom: 20px; right: 20px; background-color: #25D366; color: white; padding: 12px 16px; border-radius: 50%; box-shadow: 0 2px 10px rgba(0,0,0,0.3); z-index: 1000;">
   <img src="https://img.icons8.com/ios-filled/30/ffffff/whatsapp.png" alt="WhatsApp" />
</a>

<!--End of Tawk.to Script-->
    </body>
</html>
