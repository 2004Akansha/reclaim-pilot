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


    {{-- Inter font --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    {{-- Tailwind --}}

    <script src="https://cdn.tailwindcss.com"></script>

    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        reclaim: {

                            blue: '#111d8f',

                            red: '#b04c4c',

                            olive: '#777943',

                            lavender: '#f7f6fc',

                            yellow: '#f3e8a4'

                        }

                    }

                }

            }

        };

    </script>


    <style>

        * {
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
        }


        body {

            margin: 0;

            font-family:
                'Inter',
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background: #f7f6fc;

            color: #17171b;

        }


        .card-shadow {

            box-shadow:
                0 1px 2px rgba(17, 24, 39, 0.03),
                0 8px 30px rgba(17, 24, 39, 0.035);

        }


        /*
        |--------------------------------------------------------------------------
        | Main case shell
        |--------------------------------------------------------------------------
        */

        .case-shell {

            min-height: calc(100vh - 73px);

            display: grid;

            grid-template-columns:
                minmax(280px, 330px)
                minmax(0, 1fr);

        }


        /*
        |--------------------------------------------------------------------------
        | Sidebar
        |--------------------------------------------------------------------------
        */

        .case-sidebar {

            background: #f7f6fc;

            border-right:
                1px solid #dedde8;

            padding:
                34px 30px 30px;

            display: flex;

            flex-direction: column;

        }


        /*
        |--------------------------------------------------------------------------
        | Main content
        |--------------------------------------------------------------------------
        */

        .case-main {

            background: #ffffff;

            padding:
                48px
                56px
                80px;

            min-width: 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Document-style section
        |--------------------------------------------------------------------------
        */

        .document-section {

            position: relative;

            padding-left: 28px;

        }


        .document-section::before {

            content: "";

            position: absolute;

            left: 6px;

            top: 6px;

            bottom: 0;

            width: 1px;

            background: #d7d7dc;

        }


        .document-marker {

            position: absolute;

            left: 0;

            top: 5px;

            width: 13px;

            height: 13px;

            border-radius: 999px;

            background: #b04c4c;

            box-shadow:
                0 0 0 4px #ffffff;

        }


        .document-marker-olive {

            background: #777943;

        }


        .section-label {

            display: inline-flex;

            align-items: center;

            padding:
                6px 11px;

            border-radius: 5px;

            background: #f3e8a4;

            color: #4d4d29;

            font-size: 11px;

            font-weight: 800;

            letter-spacing: .08em;

            text-transform: uppercase;

        }


        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1024px) {

            .case-shell {

                grid-template-columns: 1fr;

            }


            .case-sidebar {

                border-right: 0;

                border-bottom:
                    1px solid #dedde8;

                padding:
                    24px;

            }


            .case-main {

                padding:
                    36px
                    24px
                    60px;

            }

        }


        @media (max-width: 640px) {

            .case-sidebar {

                padding:
                    20px 18px;

            }


            .case-main {

                padding:
                    28px 18px
                    50px;

            }


            .case-shell {

                min-height:
                    calc(100vh - 65px);

            }

        }

    </style>


    @stack('styles')

</head>


<body>


{{-- ============================================================= --}}
{{-- HEADER --}}
{{-- ============================================================= --}}

<header
    class="
        sticky top-0 z-50
        border-b border-gray-200
        bg-white/95 backdrop-blur
    "
>

    <div
        class="
            mx-auto
            flex
            h-[73px]
            max-w-[1500px]
            items-center
            justify-between
            px-5
            sm:px-8
        "
    >

        {{-- Brand --}}

        <a
            href="{{ url('/') }}"
            class="flex items-center gap-3"
        >

            <div
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    bg-[#111d8f]
                    text-white
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3l7 4v5c0 4.7-3 7.7-7 9-4-1.3-7-4.3-7-9V7l7-4z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.5 12l1.7 1.7 3.5-3.5"
                    />
                </svg>

            </div>


            <div>

                <div class="text-sm font-bold tracking-tight text-gray-950">
                    ReclaimPilot
                </div>

                <div class="hidden text-[10px] text-gray-400 sm:block">
                    Account recovery assistant
                </div>

            </div>

        </a>


        {{-- Navigation --}}

        @auth

            <div class="flex items-center gap-4 sm:gap-7">

                <a
                    href="{{ route('dashboard') }}"
                    class="
                        text-sm
                        font-medium
                        text-gray-600
                        transition
                        hover:text-[#111d8f]
                    "
                >
                    Dashboard
                </a>


                <a
                    href="{{ route('case.create') }}"
                    class="
                        hidden
                        text-sm
                        font-medium
                        text-gray-600
                        transition
                        hover:text-[#111d8f]
                        sm:block
                    "
                >
                    New Case
                </a>


                <span
                    class="
                        hidden
                        text-sm
                        text-gray-500
                        md:block
                    "
                >
                    {{ Auth::user()->name }}
                </span>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="
                            text-sm
                            font-medium
                            text-gray-500
                            transition
                            hover:text-[#b04c4c]
                        "
                    >
                        Logout
                    </button>

                </form>

            </div>

        @endauth

    </div>

</header>


{{-- ============================================================= --}}
{{-- MAIN --}}
{{-- ============================================================= --}}

<main class="min-h-[calc(100vh-73px)]">

    @yield('content')

</main>


{{-- ============================================================= --}}
{{-- FOOTER --}}
{{-- ============================================================= --}}

<footer
    class="
        border-t
        border-gray-200
        bg-white
    "
>

    <div
        class="
            mx-auto
            max-w-[1500px]
            px-5
            py-5
            text-center
            text-xs
            text-gray-400
            sm:px-8
        "
    >

        © {{ date('Y') }} ReclaimPilot.
        All rights reserved.

    </div>

</footer>


@stack('scripts')

</body>

</html>