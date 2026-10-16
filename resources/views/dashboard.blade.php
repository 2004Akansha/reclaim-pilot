@extends('layouts.reclaimpilot')

@section('title', 'Dashboard - ReclaimPilot')

@section('content')

<div class="min-h-[calc(100vh-73px)] bg-[#f7f6fc]">

    <div class="mx-auto w-full max-w-[1500px] px-5 py-10 sm:px-8 lg:py-12">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

            <div>

                {{-- Small label --}}
                <div class="inline-flex items-center gap-2">

                    <span
                        class="h-2 w-2 rounded-full bg-[#b04c4c]"
                    ></span>

                    <span
                        class="text-[11px] font-bold uppercase tracking-[0.18em] text-gray-500"
                    >
                        Recovery workspace
                    </span>

                </div>


                {{-- Heading --}}
                <h1
                    class="mt-3 text-3xl font-bold tracking-tight text-gray-950 sm:text-4xl"
                >
                    My Recovery Cases
                </h1>


                {{-- Description --}}
                <p
                    class="mt-3 max-w-2xl text-sm leading-6 text-gray-600 sm:text-base"
                >
                    Track your account recovery cases, review analysis,
                    prepare evidence, and continue your recovery journey.
                </p>

            </div>


            {{-- New Case Button --}}
            <div>

                <a
                    href="{{ route('case.create') }}"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-[#111d8f]
                        px-5
                        py-3
                        text-sm
                        font-semibold
                        text-white
                        shadow-sm
                        transition
                        hover:bg-[#0d176f]
                        focus:outline-none
                        focus:ring-2
                        focus:ring-[#111d8f]
                        focus:ring-offset-2
                    "
                >

                    {{-- Plus icon --}}
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="h-4 w-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 5v14M5 12h14"
                        />
                    </svg>

                    New Recovery Case

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- QUICK STATS --}}
        {{-- ========================================================= --}}

        <div
            class="
                mt-10
                grid
                grid-cols-1
                gap-4
                sm:grid-cols-2
                xl:grid-cols-4
            "
        >

            {{-- Total Cases --}}
            <div
                class="
                    rounded-2xl
                    border
                    border-[#dedde8]
                    bg-white
                    p-5
                    card-shadow
                "
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p
                            class="
                                text-[11px]
                                font-bold
                                uppercase
                                tracking-[0.14em]
                                text-gray-400
                            "
                        >
                            Total cases
                        </p>

                        <p
                            class="mt-3 text-3xl font-bold tracking-tight text-gray-950"
                        >
                            {{ $totalCases }}
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            items-center
                            justify-center
                            rounded-xl
                            bg-[#f1f1fb]
                            text-[#111d8f]
                        "
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.7"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12h6m-6 4h6M9 8h6m2 13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"
                            />
                        </svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    All recovery cases in your workspace.
                </p>

            </div>


            {{-- Diagnosing --}}
            <div
                class="
                    rounded-2xl
                    border
                    border-[#dedde8]
                    bg-white
                    p-5
                    card-shadow
                "
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p
                            class="
                                text-[11px]
                                font-bold
                                uppercase
                                tracking-[0.14em]
                                text-gray-400
                            "
                        >
                            Diagnosing
                        </p>

                        <p
                            class="mt-3 text-3xl font-bold tracking-tight text-gray-950"
                        >
                            {{ $diagnosingCases }}
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            items-center
                            justify-center
                            rounded-xl
                            bg-[#f3e8a4]
                            text-[#4d4d29]
                        "
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.7"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6v6l4 2"
                            />

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />
                        </svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Cases currently being diagnosed.
                </p>

            </div>


            {{-- Prepared --}}
            <div
                class="
                    rounded-2xl
                    border
                    border-[#dedde8]
                    bg-white
                    p-5
                    card-shadow
                "
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p
                            class="
                                text-[11px]
                                font-bold
                                uppercase
                                tracking-[0.14em]
                                text-gray-400
                            "
                        >
                            Prepared
                        </p>

                        <p
                            class="mt-3 text-3xl font-bold tracking-tight text-gray-950"
                        >
                            {{ $preparedCases }}
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            items-center
                            justify-center
                            rounded-xl
                            bg-[#eef0e2]
                            text-[#777943]
                        "
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.7"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m5 12 4 4L19 6"
                            />
                        </svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Cases with recovery materials prepared.
                </p>

            </div>


            {{-- Escalated --}}
            <div
                class="
                    rounded-2xl
                    border
                    border-[#dedde8]
                    bg-white
                    p-5
                    card-shadow
                "
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p
                            class="
                                text-[11px]
                                font-bold
                                uppercase
                                tracking-[0.14em]
                                text-gray-400
                            "
                        >
                            Escalated
                        </p>

                        <p
                            class="mt-3 text-3xl font-bold tracking-tight text-gray-950"
                        >
                            {{ $escalatedCases }}
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            items-center
                            justify-center
                            rounded-xl
                            bg-[#f5e6e6]
                            text-[#b04c4c]
                        "
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.7"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 5v14m0 0 6-6m-6 6-6-6"
                            />
                        </svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Cases that have moved to escalation.
                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- RECOVERY CASES SECTION --}}
        {{-- ========================================================= --}}

        <div class="mt-12">

            {{-- Section Header --}}
            <div
                class="
                    flex
                    flex-col
                    gap-4
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                "
            >

                <div>

                    <div
                        class="
                            text-[11px]
                            font-bold
                            uppercase
                            tracking-[0.18em]
                            text-gray-400
                        "
                    >
                        Your workspace
                    </div>

                    <h2
                        class="mt-2 text-2xl font-bold tracking-tight text-gray-950"
                    >
                        Recovery Cases
                    </h2>

                    <p
                        class="mt-2 text-sm leading-6 text-gray-500"
                    >
                        Continue working on an existing recovery case.
                    </p>

                </div>


                @if($totalCases > 0)

                    <a
                        href="{{ route('case.create') }}"
                        class="
                            inline-flex
                            items-center
                            gap-2
                            text-sm
                            font-semibold
                            text-[#111d8f]
                            transition
                            hover:text-[#0d176f]
                        "
                    >

                        Start another case

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14m-6-6 6 6-6 6"
                            />
                        </svg>

                    </a>

                @endif

            </div>


            {{-- ===================================================== --}}
            {{-- CASE LIST --}}
            {{-- ===================================================== --}}

            <div class="mt-6 space-y-4">

                @forelse($cases as $case)

                    @php

                        $issueNames = [
                            'account_disabled' => 'Account disabled',
                            'hacked_account' => 'Hacked account',
                            'cant_login' => "Can't log in",
                            'other' => 'Other account issue',
                        ];

                        $statusNames = [
                            'diagnosing' => 'Diagnosing',
                            'analyzing' => 'Analyzing',
                            'analyzed' => 'Analysis ready',
                            'prepared' => 'Prepared',
                            'escalated' => 'Escalated',
                            'completed' => 'Completed',
                        ];

                        $statusClasses = [
                            'diagnosing' => 'bg-[#f1f1fb] text-[#111d8f] border-[#d9daf2]',
                            'analyzing' => 'bg-[#f3e8a4] text-[#4d4d29] border-[#e5d98e]',
                            'analyzed' => 'bg-[#f1f1fb] text-[#111d8f] border-[#d9daf2]',
                            'prepared' => 'bg-[#eef0e2] text-[#777943] border-[#d9ddc5]',
                            'escalated' => 'bg-[#f5e6e6] text-[#b04c4c] border-[#e8caca]',
                            'completed' => 'bg-gray-100 text-gray-700 border-gray-200',
                        ];

                        /*
                        |--------------------------------------------------------------------------
                        | Decide where the user should continue.
                        |--------------------------------------------------------------------------
                        */

                        $caseRoute = route('case.analyze', $case);

                        if ($case->status === 'prepared') {
                            $caseRoute = route('case.prepare', $case);
                        }

                        if ($case->status === 'escalated') {
                            $caseRoute = route('case.escalate', $case);
                        }

                    @endphp


                    {{-- ================================================= --}}
                    {{-- CASE CARD --}}
                    {{-- ================================================= --}}

                    <a
                        href="{{ $caseRoute }}"
                        class="
                            group
                            block
                            rounded-2xl
                            border
                            border-[#dedde8]
                            bg-white
                            p-5
                            card-shadow
                            transition
                            hover:-translate-y-0.5
                            hover:border-[#c9c8d5]
                            hover:shadow-lg
                        "
                    >

                        <div
                            class="
                                flex
                                flex-col
                                gap-5
                                lg:flex-row
                                lg:items-center
                                lg:justify-between
                            "
                        >

                            {{-- ----------------------------------------- --}}
                            {{-- LEFT SIDE --}}
                            {{-- ----------------------------------------- --}}

                            <div class="flex min-w-0 items-start gap-4">

                                {{-- Case icon --}}
                                <div
                                    class="
                                        flex
                                        h-11
                                        w-11
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-[#f1f1fb]
                                        text-[#111d8f]
                                    "
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.7"
                                        stroke="currentColor"
                                        class="h-5 w-5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6M9 8h6m2 13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"
                                        />
                                    </svg>

                                </div>


                                {{-- Case information --}}
                                <div class="min-w-0">

                                    <div
                                        class="
                                            flex
                                            flex-wrap
                                            items-center
                                            gap-2
                                        "
                                    >

                                        <h3
                                            class="
                                                text-base
                                                font-bold
                                                text-gray-950
                                            "
                                        >
                                            Case #{{ $case->id }}
                                        </h3>


                                        <span
                                            class="
                                                text-xs
                                                text-gray-400
                                            "
                                        >
                                            •
                                        </span>


                                        <span
                                            class="
                                                text-xs
                                                text-gray-400
                                            "
                                        >
                                            {{ $case->created_at->format('M d, Y') }}
                                        </span>

                                    </div>


                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            font-semibold
                                            text-gray-800
                                        "
                                    >
                                        {{ $issueNames[$case->issue_type] ?? 'Other account issue' }}
                                    </p>


                                    @if($case->description)

                                        <p
                                            class="
                                                mt-1
                                                max-w-2xl
                                                truncate
                                                text-sm
                                                leading-6
                                                text-gray-500
                                            "
                                        >
                                            {{ $case->description }}
                                        </p>

                                    @else

                                        <p
                                            class="
                                                mt-1
                                                text-sm
                                                italic
                                                text-gray-400
                                            "
                                        >
                                            No description provided.
                                        </p>

                                    @endif

                                </div>

                            </div>


                            {{-- ----------------------------------------- --}}
                            {{-- RIGHT SIDE --}}
                            {{-- ----------------------------------------- --}}

                            <div
                                class="
                                    flex
                                    shrink-0
                                    items-center
                                    justify-between
                                    gap-4
                                    lg:justify-end
                                "
                            >

                                {{-- Status --}}
                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        border
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-bold
                                        {{ $statusClasses[$case->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}
                                    "
                                >

                                    <span
                                        class="
                                            h-1.5
                                            w-1.5
                                            rounded-full
                                            bg-current
                                        "
                                    ></span>

                                    {{ $statusNames[$case->status] ?? ucfirst($case->status) }}

                                </span>


                                {{-- Continue arrow --}}
                                <div
                                    class="
                                        flex
                                        h-9
                                        w-9
                                        items-center
                                        justify-center
                                        rounded-lg
                                        border
                                        border-gray-200
                                        text-gray-400
                                        transition
                                        group-hover:border-[#c9c8d5]
                                        group-hover:bg-[#f7f6fc]
                                        group-hover:text-[#111d8f]
                                    "
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m9 5 7 7-7 7"
                                        />
                                    </svg>

                                </div>

                            </div>

                        </div>


                        {{-- ----------------------------------------- --}}
                        {{-- CASE PROGRESS --}}
                        {{-- ----------------------------------------- --}}

                        <div
                            class="
                                mt-5
                                border-t
                                border-gray-100
                                pt-4
                            "
                        >

                            <div
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    text-[11px]
                                    font-semibold
                                    text-gray-400
                                "
                            >

                                <span>
                                    Recovery journey
                                </span>

                                <span>
                                    @switch($case->status)

                                        @case('diagnosing')
                                            Step 1 of 4
                                            @break

                                        @case('analyzing')
                                        @case('analyzed')
                                            Step 2 of 4
                                            @break

                                        @case('prepared')
                                            Step 3 of 4
                                            @break

                                        @case('escalated')
                                        @case('completed')
                                            Step 4 of 4
                                            @break

                                        @default
                                            Step 1 of 4

                                    @endswitch
                                </span>

                            </div>


                            {{-- Progress bar --}}
                            <div
                                class="
                                    mt-2
                                    h-1.5
                                    overflow-hidden
                                    rounded-full
                                    bg-gray-100
                                "
                            >

                                @php

                                    $progressWidth = match ($case->status) {
                                        'diagnosing' => '25%',
                                        'analyzing',
                                        'analyzed' => '50%',
                                        'prepared' => '75%',
                                        'escalated',
                                        'completed' => '100%',
                                        default => '25%',
                                    };

                                @endphp


                                <div
                                    class="h-full rounded-full bg-[#b04c4c]"
                                    style="width: {{ $progressWidth }}"
                                ></div>

                            </div>

                        </div>

                    </a>

                @empty

                    {{-- ================================================= --}}
                    {{-- EMPTY STATE --}}
                    {{-- ================================================= --}}

                    <div
                        class="
                            rounded-2xl
                            border
                            border-dashed
                            border-[#cfcfd8]
                            bg-white
                            px-6
                            py-16
                            text-center
                            card-shadow
                        "
                    >

                        {{-- Icon --}}
                        <div
                            class="
                                mx-auto
                                flex
                                h-16
                                w-16
                                items-center
                                justify-center
                                rounded-2xl
                                bg-[#f1f1fb]
                                text-[#111d8f]
                            "
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.6"
                                stroke="currentColor"
                                class="h-8 w-8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6M9 8h6m2 13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"
                                />
                            </svg>

                        </div>


                        <div
                            class="
                                mx-auto
                                mt-6
                                inline-flex
                                items-center
                                rounded-md
                                bg-[#f3e8a4]
                                px-3
                                py-1.5
                                text-[10px]
                                font-extrabold
                                uppercase
                                tracking-[0.12em]
                                text-[#4d4d29]
                            "
                        >
                            Start your recovery journey
                        </div>


                        <h3
                            class="
                                mt-5
                                text-xl
                                font-bold
                                tracking-tight
                                text-gray-950
                            "
                        >
                            No recovery cases yet
                        </h3>


                        <p
                            class="
                                mx-auto
                                mt-3
                                max-w-lg
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            Create your first recovery case to describe
                            what happened, analyze the issue, prepare your
                            evidence, and continue with the recovery process.
                        </p>


                        <a
                            href="{{ route('case.create') }}"
                            class="
                                mt-7
                                inline-flex
                                items-center
                                gap-2
                                rounded-lg
                                bg-[#111d8f]
                                px-5
                                py-3
                                text-sm
                                font-semibold
                                text-white
                                shadow-sm
                                transition
                                hover:bg-[#0d176f]
                                focus:outline-none
                                focus:ring-2
                                focus:ring-[#111d8f]
                                focus:ring-offset-2
                            "
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="h-4 w-4"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 5v14M5 12h14"
                                />
                            </svg>

                            Start a Recovery Case

                        </a>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SAFETY NOTICE --}}
        {{-- ========================================================= --}}

        <div
            class="
                mt-10
                rounded-2xl
                border
                border-[#dedde8]
                bg-white
                p-5
                card-shadow
            "
        >

            <div class="flex items-start gap-4">

                <div
                    class="
                        flex
                        h-10
                        w-10
                        shrink-0
                        items-center
                        justify-center
                        rounded-xl
                        bg-[#f1f1fb]
                        text-[#111d8f]
                    "
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.7"
                        stroke="currentColor"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.2 12.48A2 2 0 004.82 19h14.36a2 2 0 001.73-2.66l-7.2-12.48a2 2 0 00-3.46 0z"
                        />
                    </svg>

                </div>


                <div>

                    <h3
                        class="text-sm font-bold text-gray-950"
                    >
                        Keep your recovery information safe
                    </h3>

                    <p
                        class="
                            mt-1
                            max-w-3xl
                            text-xs
                            leading-5
                            text-gray-500
                        "
                    >
                        Never share your password, authentication codes,
                        recovery codes, or other sensitive credentials.
                        ReclaimPilot helps organize your recovery process
                        but does not need these secrets.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection