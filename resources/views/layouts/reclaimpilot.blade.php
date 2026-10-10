<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'ReclaimPilot')
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        reclaim: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b'
                        }
                    }
                }
            }
        }
    </script>

    <style>

        body {
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .card-shadow {
            box-shadow:
                0 4px 14px rgba(15, 23, 42, 0.04);
        }

    </style>

    @stack('styles')

</head>

<body class="bg-[#f8faf9] text-gray-900">

    {{-- Header --}}

    <header class="bg-white border-b border-gray-100">

        <div
            class="max-w-6xl mx-auto px-5 sm:px-6 py-5 flex items-center justify-between"
        >

            {{-- Brand --}}

            <a
                href="{{ url('/') }}"
                class="flex items-center gap-3"
            >

                <div
                    class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center"
                >

                    <div
                        class="w-4 h-4 rounded-full bg-white opacity-90"
                    ></div>

                </div>

                <span class="text-lg font-bold tracking-tight">
                    ReclaimPilot
                </span>

            </a>


            {{-- User icon --}}

            <div
                class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center"
            >

                <svg
                    class="w-5 h-5 text-gray-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 9a3 3 0 11-6 0 3 3 0 016 0z"
                    />

                </svg>

            </div>

        </div>

    </header>


    {{-- Main page --}}

    <main class="max-w-6xl mx-auto px-5 sm:px-6 py-8 sm:py-10">

        @yield('content')

    </main>


    @stack('scripts')

</body>

</html>