<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Inline script to handle initial color mode --}}
    <script>
        (function() {
            const appearance = '{{ $appearance ?? "auto" }}';
            const html = document.documentElement;

            let theme = appearance;

            if (appearance === 'auto') {
                // Check system preference
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                theme = prefersDark ? 'dark' : 'light';
            }

            // Apply theme immediately to prevent flash
            html.classList.remove('light', 'dark');
            html.classList.add(theme);

            // Set cookie with proper settings
            const expires = new Date();
            expires.setFullYear(expires.getFullYear() + 1);
            document.cookie = `appearance=${appearance}; path=/; samesite=lax; expires=${expires.toUTCString()}${window.location.protocol === 'https:' ? '; secure' : ''}`;
        })();
    </script>

    <title inertia>{{ config('app.name', 'MarJose123/starter-kit') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @routes
    @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
