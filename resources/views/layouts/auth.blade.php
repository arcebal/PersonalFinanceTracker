<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TrackerYarn - @yield('title','Auth')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        .gradient-bg{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)}
        .gradient-text{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <div class="min-h-screen flex items-center justify-center">
        <div class="absolute top-6 left-6 flex items-center space-x-3">
            <!-- question mark icon -->
            <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 14a1 1 0 110 2 1 1 0 010-2zm1.07-7.75c.2.23.33.52.33.83 0 .7-.56 1.07-1.42 1.6-.8.49-1.24.86-1.24 1.82v.5a1 1 0 11-2 0v-.5c0-1.32.7-2.06 1.6-2.6.83-.51 1.46-.9 1.46-1.82 0-.6-.34-1.2-.94-1.56A2 2 0 1010 6a1 1 0 112 0c0 .26.19.51.43.75z" clip-rule="evenodd"/></svg>
            <span class="text-white text-2xl font-bold">TrackerYarn</span>
        </div>

        <div class="w-full max-w-md mx-auto p-6">
            <div class="">
                <div>
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="mb-6 flex items-center space-x-3">
                            <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 14a1 1 0 110 2 1 1 0 010-2zm1.07-7.75c.2.23.33.52.33.83 0 .7-.56 1.07-1.42 1.6-.8.49-1.24.86-1.24 1.82v.5a1 1 0 11-2 0v-.5c0-1.32.7-2.06 1.6-2.6.83-.51 1.46-.9 1.46-1.82 0-.6-.34-1.2-.94-1.56A2 2 0 1010 6a1 1 0 112 0c0 .26.19.51.43.75z" clip-rule="evenodd"/></svg>
                            <div>
                                <div class="text-lg font-semibold">TrackerYarn</div>
                                <div class="text-sm text-gray-500">Sign in to your account</div>
                            </div>
                        </div>

                        @yield('content')

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>