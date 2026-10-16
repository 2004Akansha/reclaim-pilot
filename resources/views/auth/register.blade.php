@extends('layouts.reclaimpilot')

@section('title', 'Create Account - ReclaimPilot')

@section('content')

<div
    class="
        min-h-[calc(100vh-180px)]
        flex
        items-center
        py-10
        sm:py-14
    "
>

    <div
        class="
            w-full
            max-w-6xl
            mx-auto
        "
    >

        {{-- ===================================================== --}}
        {{-- REGISTER DOCUMENT --}}
        {{-- ===================================================== --}}

        <div
            class="
                grid
                grid-cols-1
                lg:grid-cols-[0.9fr_1.1fr]
                border
                border-gray-200
                bg-white
            "
        >

            {{-- ================================================= --}}
            {{-- LEFT INFORMATION PANEL --}}
            {{-- ================================================= --}}

            <div
                class="
                    relative
                    border-b
                    border-gray-200
                    bg-[#f7f7fb]
                    p-7
                    sm:p-10
                    lg:border-b-0
                    lg:border-r
                    lg:p-12
                "
            >

                {{-- Vertical document line --}}

                <div
                    class="
                        absolute
                        left-8
                        top-12
                        bottom-12
                        hidden
                        w-px
                        bg-gray-300
                        sm:block
                        lg:left-12
                    "
                ></div>


                <div
                    class="
                        relative
                        sm:pl-8
                        lg:pl-10
                    "
                >

                    {{-- ================================================= --}}
                    {{-- SECTION MARKER --}}
                    {{-- ================================================= --}}

                    <div class="mb-8">

                        <div class="flex items-center gap-3">

                            <span
                                class="
                                    flex
                                    h-3
                                    w-3
                                    shrink-0
                                    rounded-full
                                    bg-[#b64b4b]
                                    ring-4
                                    ring-[#f7f7fb]
                                "
                            ></span>

                            <span
                                class="
                                    text-xs
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-500
                                "
                            >
                                Create account
                            </span>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- MAIN HEADING --}}
                    {{-- ================================================= --}}

                    <h1
                        class="
                            max-w-md
                            text-3xl
                            font-bold
                            tracking-tight
                            text-gray-950
                            sm:text-4xl
                        "
                    >
                        Start your recovery
                        <span class="text-[#111d8f]">
                            workspace
                        </span>
                    </h1>


                    <p
                        class="
                            mt-5
                            max-w-md
                            text-sm
                            leading-7
                            text-gray-500
                        "
                    >
                        Create a ReclaimPilot account to prepare,
                        analyze and organize your account recovery case
                        in one place.
                    </p>


                    {{-- ================================================= --}}
                    {{-- RECOVERY FLOW --}}
                    {{-- ================================================= --}}

                    <div class="mt-10 space-y-7">


                        {{-- ============================================= --}}
                        {{-- STEP 1 --}}
                        {{-- ============================================= --}}

                        <div class="relative flex gap-4">

                            <div
                                class="
                                    relative
                                    z-10
                                    flex
                                    h-9
                                    w-9
                                    shrink-0
                                    items-center
                                    justify-center
                                    border
                                    border-[#111d8f]
                                    bg-white
                                    text-sm
                                    font-bold
                                    text-[#111d8f]
                                "
                            >
                                1
                            </div>


                            <div>

                                <h2
                                    class="
                                        text-sm
                                        font-bold
                                        text-gray-900
                                    "
                                >
                                    Diagnose
                                </h2>

                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-gray-500
                                    "
                                >
                                    Tell us what happened to your
                                    account.
                                </p>

                            </div>

                        </div>


                        {{-- ============================================= --}}
                        {{-- STEP 2 --}}
                        {{-- ============================================= --}}

                        <div class="relative flex gap-4">

                            <div
                                class="
                                    relative
                                    z-10
                                    flex
                                    h-9
                                    w-9
                                    shrink-0
                                    items-center
                                    justify-center
                                    border
                                    border-gray-300
                                    bg-white
                                    text-sm
                                    font-bold
                                    text-gray-500
                                "
                            >
                                2
                            </div>


                            <div>

                                <h2
                                    class="
                                        text-sm
                                        font-bold
                                        text-gray-900
                                    "
                                >
                                    Analyze
                                </h2>

                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-gray-500
                                    "
                                >
                                    Review the likely cause and
                                    recommended recovery action.
                                </p>

                            </div>

                        </div>


                        {{-- ============================================= --}}
                        {{-- STEP 3 --}}
                        {{-- ============================================= --}}

                        <div class="relative flex gap-4">

                            <div
                                class="
                                    relative
                                    z-10
                                    flex
                                    h-9
                                    w-9
                                    shrink-0
                                    items-center
                                    justify-center
                                    border
                                    border-gray-300
                                    bg-white
                                    text-sm
                                    font-bold
                                    text-gray-500
                                "
                            >
                                3
                            </div>


                            <div>

                                <h2
                                    class="
                                        text-sm
                                        font-bold
                                        text-gray-900
                                    "
                                >
                                    Prepare
                                </h2>

                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-gray-500
                                    "
                                >
                                    Prepare the information and
                                    recovery request you need.
                                </p>

                            </div>

                        </div>


                        {{-- ============================================= --}}
                        {{-- STEP 4 --}}
                        {{-- ============================================= --}}

                        <div class="relative flex gap-4">

                            <div
                                class="
                                    relative
                                    z-10
                                    flex
                                    h-9
                                    w-9
                                    shrink-0
                                    items-center
                                    justify-center
                                    border
                                    border-gray-300
                                    bg-white
                                    text-sm
                                    font-bold
                                    text-gray-500
                                "
                            >
                                4
                            </div>


                            <div>

                                <h2
                                    class="
                                        text-sm
                                        font-bold
                                        text-gray-900
                                    "
                                >
                                    Escalate
                                </h2>

                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-gray-500
                                    "
                                >
                                    Continue through the appropriate
                                    official recovery process.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- SECURITY NOTE --}}
                    {{-- ================================================= --}}

                    <div
                        class="
                            mt-10
                            border-t
                            border-gray-200
                            pt-6
                        "
                    >

                        <div class="flex gap-3">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.6"
                                stroke="currentColor"
                                class="
                                    mt-0.5
                                    h-5
                                    w-5
                                    shrink-0
                                    text-[#111d8f]
                                "
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 3l7 4v5c0 4.5-2.9 7.8-7 9-4.1-1.2-7-4.5-7-9V7l7-4z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9.5 12l1.7 1.7 3.5-3.5"
                                />

                            </svg>


                            <p
                                class="
                                    text-xs
                                    leading-5
                                    text-gray-500
                                "
                            >
                                Your recovery information is used to
                                prepare your case. Never share passwords,
                                authentication codes, or recovery codes.
                            </p>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- RIGHT REGISTER FORM --}}
            {{-- ================================================= --}}

            <div
                class="
                    bg-white
                    p-7
                    sm:p-10
                    lg:p-12
                "
            >

                {{-- ================================================= --}}
                {{-- FORM HEADING --}}
                {{-- ================================================= --}}

                <div class="mb-9">

                    <span
                        class="
                            text-xs
                            font-bold
                            uppercase
                            tracking-[0.16em]
                            text-gray-500
                        "
                    >
                        New account
                    </span>


                    <h2
                        class="
                            mt-3
                            text-2xl
                            font-bold
                            tracking-tight
                            text-gray-950
                            sm:text-3xl
                        "
                    >
                        Create your ReclaimPilot account
                    </h2>


                    <p
                        class="
                            mt-3
                            max-w-lg
                            text-sm
                            leading-6
                            text-gray-500
                        "
                    >
                        Create an account to start preparing your
                        account recovery case and keep your recovery
                        information organized.
                    </p>

                </div>



                {{-- ================================================= --}}
                {{-- GENERAL ERRORS --}}
                {{-- ================================================= --}}

                @if ($errors->any())

                    <div
                        class="
                            mb-7
                            border-l-4
                            border-[#b64b4b]
                            bg-[#fff7f7]
                            px-4
                            py-4
                        "
                    >

                        <p
                            class="
                                text-sm
                                font-semibold
                                text-gray-900
                            "
                        >
                            We could not create your account.
                        </p>


                        <ul
                            class="
                                mt-2
                                list-disc
                                space-y-1
                                pl-5
                                text-xs
                                leading-5
                                text-red-700
                            "
                        >

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- REGISTER FORM --}}
                {{-- ================================================= --}}

                <form
                    method="POST"
                    action="{{ route('register') }}"
                >

                    @csrf


                    {{-- ================================================= --}}
                    {{-- FULL NAME --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="name"
                            class="
                                text-xs
                                font-bold
                                uppercase
                                tracking-wide
                                text-gray-500
                            "
                        >
                            Full name
                        </label>


                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Enter your full name"
                            class="
                                mt-2
                                w-full
                                border-0
                                border-b
                                border-gray-300
                                bg-transparent
                                px-0
                                py-3
                                text-sm
                                text-gray-900
                                outline-none
                                placeholder:text-gray-300
                                transition
                                focus:border-[#111d8f]
                                focus:ring-0
                            "
                        >


                        @error('name')

                            <p
                                class="
                                    mt-2
                                    text-xs
                                    text-red-600
                                "
                            >
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- ================================================= --}}
                    {{-- EMAIL --}}
                    {{-- ================================================= --}}

                    <div class="mt-8">

                        <label
                            for="email"
                            class="
                                text-xs
                                font-bold
                                uppercase
                                tracking-wide
                                text-gray-500
                            "
                        >
                            Email address
                        </label>


                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            placeholder="you@example.com"
                            class="
                                mt-2
                                w-full
                                border-0
                                border-b
                                border-gray-300
                                bg-transparent
                                px-0
                                py-3
                                text-sm
                                text-gray-900
                                outline-none
                                placeholder:text-gray-300
                                transition
                                focus:border-[#111d8f]
                                focus:ring-0
                            "
                        >


                        @error('email')

                            <p
                                class="
                                    mt-2
                                    text-xs
                                    text-red-600
                                "
                            >
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- ================================================= --}}
                    {{-- PASSWORD --}}
                    {{-- ================================================= --}}

                    <div class="mt-8">

                        <label
                            for="password"
                            class="
                                text-xs
                                font-bold
                                uppercase
                                tracking-wide
                                text-gray-500
                            "
                        >
                            Password
                        </label>


                        <div class="relative">

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Create a password"
                                class="
                                    mt-2
                                    w-full
                                    border-0
                                    border-b
                                    border-gray-300
                                    bg-transparent
                                    px-0
                                    py-3
                                    pr-12
                                    text-sm
                                    text-gray-900
                                    outline-none
                                    placeholder:text-gray-300
                                    transition
                                    focus:border-[#111d8f]
                                    focus:ring-0
                                "
                            >


                            {{-- Show / hide password --}}

                            <button
                                type="button"
                                id="togglePassword"
                                aria-label="Show password"
                                class="
                                    absolute
                                    right-0
                                    top-2
                                    inline-flex
                                    h-9
                                    w-9
                                    items-center
                                    justify-center
                                    text-gray-400
                                    transition
                                    hover:text-gray-700
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-[#111d8f]
                                    focus:ring-offset-2
                                "
                            >

                                {{-- Eye open --}}

                                <svg
                                    id="eyeOpen"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.6"
                                    stroke="currentColor"
                                    class="h-5 w-5"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12 18.25 18.25 12 18.25 2.25 12 2.25 12z"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="2.75"
                                    />

                                </svg>


                                {{-- Eye closed --}}

                                <svg
                                    id="eyeClosed"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.6"
                                    stroke="currentColor"
                                    class="hidden h-5 w-5"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 3l18 18"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M10.58 10.58a2 2 0 002.84 2.84"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9.88 5.18A9.92 9.92 0 0112 5.75c6.25 0 9.75 6.25 9.75 6.25a17.1 17.1 0 01-3.17 3.85"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.17 6.17C3.74 8.02 2.25 12 2.25 12s3.5 6.25 9.75 6.25c1.77 0 3.34-.45 4.72-1.08"
                                    />

                                </svg>

                            </button>

                        </div>


                        @error('password')

                            <p
                                class="
                                    mt-2
                                    text-xs
                                    text-red-600
                                "
                            >
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- ================================================= --}}
                    {{-- CONFIRM PASSWORD --}}
                    {{-- ================================================= --}}

                    <div class="mt-8">

                        <label
                            for="password_confirmation"
                            class="
                                text-xs
                                font-bold
                                uppercase
                                tracking-wide
                                text-gray-500
                            "
                        >
                            Confirm password
                        </label>


                        <div class="relative">

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirm your password"
                                class="
                                    mt-2
                                    w-full
                                    border-0
                                    border-b
                                    border-gray-300
                                    bg-transparent
                                    px-0
                                    py-3
                                    pr-12
                                    text-sm
                                    text-gray-900
                                    outline-none
                                    placeholder:text-gray-300
                                    transition
                                    focus:border-[#111d8f]
                                    focus:ring-0
                                "
                            >


                            {{-- Show / hide confirmation password --}}

                            <button
                                type="button"
                                id="togglePasswordConfirmation"
                                aria-label="Show confirmation password"
                                class="
                                    absolute
                                    right-0
                                    top-2
                                    inline-flex
                                    h-9
                                    w-9
                                    items-center
                                    justify-center
                                    text-gray-400
                                    transition
                                    hover:text-gray-700
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-[#111d8f]
                                    focus:ring-offset-2
                                "
                            >

                                {{-- Eye open --}}

                                <svg
                                    id="eyeConfirmationOpen"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.6"
                                    stroke="currentColor"
                                    class="h-5 w-5"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12 18.25 18.25 12 18.25 2.25 12 2.25 12z"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="2.75"
                                    />

                                </svg>


                                {{-- Eye closed --}}

                                <svg
                                    id="eyeConfirmationClosed"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.6"
                                    stroke="currentColor"
                                    class="hidden h-5 w-5"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 3l18 18"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M10.58 10.58a2 2 0 002.84 2.84"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9.88 5.18A9.92 9.92 0 0112 5.75c6.25 0 9.75 6.25 9.75 6.25a17.1 17.1 0 01-3.17 3.85"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.17 6.17C3.74 8.02 2.25 12 2.25 12s3.5 6.25 9.75 6.25c1.77 0 3.34-.45 4.72-1.08"
                                    />

                                </svg>

                            </button>

                        </div>


                        @error('password_confirmation')

                            <p
                                class="
                                    mt-2
                                    text-xs
                                    text-red-600
                                "
                            >
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- ================================================= --}}
                    {{-- CREATE ACCOUNT BUTTON --}}
                    {{-- ================================================= --}}

                    <button
                        type="submit"
                        class="
                            mt-9
                            inline-flex
                            w-full
                            items-center
                            justify-center
                            gap-3
                            bg-[#111d8f]
                            px-6
                            py-3.5
                            text-sm
                            font-semibold
                            text-white
                            transition
                            hover:bg-[#0b146d]
                            focus:outline-none
                            focus:ring-2
                            focus:ring-[#111d8f]
                            focus:ring-offset-2
                        "
                    >

                        Create ReclaimPilot account


                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14m-6-6l6 6-6 6"
                            />

                        </svg>

                    </button>

                </form>



                {{-- ================================================= --}}
                {{-- LOGIN LINK --}}
                {{-- ================================================= --}}

                @if (Route::has('login'))

                    <div
                        class="
                            mt-8
                            border-t
                            border-gray-200
                            pt-7
                            text-center
                        "
                    >

                        <p
                            class="
                                text-sm
                                text-gray-500
                            "
                        >

                            Already have an account?

                            <a
                                href="{{ route('login') }}"
                                class="
                                    font-semibold
                                    text-[#111d8f]
                                    hover:text-[#0b146d]
                                    hover:underline
                                "
                            >
                                Sign in
                            </a>

                        </p>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- SECURITY FOOTNOTE --}}
                {{-- ================================================= --}}

                <div
                    class="
                        mt-8
                        flex
                        items-start
                        gap-3
                        border-t
                        border-gray-100
                        pt-5
                    "
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="
                            mt-0.5
                            h-4
                            w-4
                            shrink-0
                            text-gray-400
                        "
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 3l7 4v5c0 4.5-2.9 7.8-7 9-4.1-1.2-7-4.5-7-9V7l7-4z"
                        />

                    </svg>


                    <p
                        class="
                            text-[11px]
                            leading-5
                            text-gray-400
                        "
                    >
                        ReclaimPilot will never ask you to provide
                        your password, authentication code, or recovery
                        code as part of your recovery case.
                    </p>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PAGE FOOTNOTE --}}
        {{-- ===================================================== --}}

        <div
            class="
                mt-5
                flex
                flex-col
                items-center
                justify-between
                gap-2
                text-center
                text-[11px]
                text-gray-400
                sm:flex-row
                sm:text-left
            "
        >

            <p>
                ReclaimPilot · Account recovery preparation
            </p>


            <p>
                Use official platform recovery channels only.
            </p>

        </div>

    </div>

</div>

@endsection



{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

@push('scripts')

<script>

    /*
    |--------------------------------------------------------------------------
    | Password visibility toggle
    |--------------------------------------------------------------------------
    */

    const togglePassword =
        document.getElementById('togglePassword');

    const passwordInput =
        document.getElementById('password');

    const eyeOpen =
        document.getElementById('eyeOpen');

    const eyeClosed =
        document.getElementById('eyeClosed');


    if (
        togglePassword &&
        passwordInput &&
        eyeOpen &&
        eyeClosed
    ) {

        togglePassword.addEventListener(
            'click',
            function () {

                const isPassword =
                    passwordInput.type === 'password';


                passwordInput.type =
                    isPassword
                        ? 'text'
                        : 'password';


                eyeOpen.classList.toggle(
                    'hidden',
                    isPassword
                );


                eyeClosed.classList.toggle(
                    'hidden',
                    !isPassword
                );


                togglePassword.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );

            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | Confirm password visibility toggle
    |--------------------------------------------------------------------------
    */

    const togglePasswordConfirmation =
        document.getElementById(
            'togglePasswordConfirmation'
        );

    const passwordConfirmationInput =
        document.getElementById(
            'password_confirmation'
        );

    const eyeConfirmationOpen =
        document.getElementById(
            'eyeConfirmationOpen'
        );

    const eyeConfirmationClosed =
        document.getElementById(
            'eyeConfirmationClosed'
        );


    if (
        togglePasswordConfirmation &&
        passwordConfirmationInput &&
        eyeConfirmationOpen &&
        eyeConfirmationClosed
    ) {

        togglePasswordConfirmation.addEventListener(
            'click',
            function () {

                const isPassword =
                    passwordConfirmationInput.type === 'password';


                passwordConfirmationInput.type =
                    isPassword
                        ? 'text'
                        : 'password';


                eyeConfirmationOpen.classList.toggle(
                    'hidden',
                    isPassword
                );


                eyeConfirmationClosed.classList.toggle(
                    'hidden',
                    !isPassword
                );


                togglePasswordConfirmation.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Hide confirmation password'
                        : 'Show confirmation password'
                );

            }
        );

    }

</script>

@endpush