<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PREDATOR</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased text-white">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            <div class="flex flex-col justify-center items-center">
                <img src="{{url('/images/predator.png')}}" alt="logo" class="rounded-3xl h-72">
                <h1 class="text-7xl my-2 font-extrabold hover:text-transparent bg-clip-text bg-gradient-to-br from-white to-blue-600 duration-700">PREDATOR</h1>
                <div class="flex flex-col my-5">
                    @if (Route::has('login'))
                    <div>
                        @auth
                        <button class="relative inline-flex items-center justify-center p-0.5 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                            <span class="relative px-5 py-2.5 transition-all ease-in duration-200 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                <a href="{{ url('/home') }}" class="font-semibold text-xl">Home</a>
                            </span>
                          </button>
                        @else
                        <button class="relative inline-flex items-center justify-center p-0.5 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-blue-600 to-cyan-200 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800">
                            <span class="relative px-5 py-2.5 transition-all ease-in duration-200 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                <a href="{{ route('login') }}" class="font-semibold text-lg">Log in</a>
                            </span>
                          </button>
                            
    
                            @if (Route::has('register'))
                            <button class="relative inline-flex items-center justify-center p-0.5 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-teal-300 to-lime-300 group-hover:from-teal-300 group-hover:to-lime-300 dark:text-white dark:hover:text-gray-900 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-lime-800">
                                <span class="relative px-5 py-2.5 transition-all ease-in duration-200 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                    <a href="{{ route('register') }}" class="font-semibold text-lg">Register</a>
                                </span>
                              </button>
                                
                            @endif
                        @endauth
                    </div>
                    @endif
                </div>
            </div>
        
        </div>
    </body>
</html>
