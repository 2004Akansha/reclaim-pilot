@extends('layouts.reclaimpilot')

@section('title', 'ReclaimPilot - Recovery Options')

@section('content')

    <x-case-shell
        :current-step="4"
        :case="$case"
    >

        {{-- ========================================================= --}}
        {{-- HEADING --}}
        {{-- ========================================================= --}}

        <div class="mb-12">

            <span class="section-label">
                Step 04 · Recovery
            </span>


            <h1
                class="
                    mt-5
                    text-4xl
                    font-bold
                    tracking-tight
                    text-gray-950
                "
            >
                Take the next step
            </h1>


            <p
                class="
                    mt-4
                    max-w-2xl
                    text-base
                    leading-7
                    text-gray-500
                "
            >
                Your case is prepared. Review your recovery plan and
                continue through the platform's official recovery options.
            </p>

        </div>


        {{-- ========================================================= --}}
        {{-- RECOMMENDED ACTION --}}
        {{-- ========================================================= --}}

        <section class="document-section mb-12">

            <span class="document-marker"></span>


            <div class="section-label">
                Recommended action
            </div>


            <h2
                class="
                    mt-5
                    text-2xl
                    font-bold
                    tracking-tight
                    text-gray-950
                "
            >
                {{ $recommendation['title'] }}
            </h2>


            <p
                class="
                    mt-3
                    max-w-2xl
                    text-sm
                    leading-7
                    text-gray-600
                "
            >
                {{ $recommendation['description'] }}
            </p>


            <a
                href="{{ route('case.prepare', $case) }}"
                class="
                    mt-6
                    inline-flex
                    items-center
                    gap-3
                    bg-[#111d8f]
                    px-6
                    py-3
                    text-sm
                    font-semibold
                    text-white
                    transition
                    hover:bg-[#0b146d]
                "
            >

                {{ $recommendation['button'] }}

                <span>
                    →
                </span>

            </a>

        </section>


        {{-- ========================================================= --}}
        {{-- RECOVERY PATH --}}
        {{-- ========================================================= --}}

        <section class="document-section mb-12">

            <span class="document-marker document-marker-olive"></span>


            <div class="section-label">
                Recovery path
            </div>


            <h2
                class="
                    mt-5
                    text-2xl
                    font-bold
                    tracking-tight
                    text-gray-950
                "
            >
                What happens next
            </h2>


            @php

                $steps = [
                    [
                        'title' => 'Review your appeal',
                        'description' =>
                            'Check that your explanation and account details are accurate.',
                        'status' => 'Do now',
                    ],

                    [
                        'title' => 'Use the official recovery process',
                        'description' =>
                            'Submit your request through the platform’s official support or recovery channel.',
                        'status' => 'Next',
                    ],

                    [
                        'title' => 'Provide verification if requested',
                        'description' =>
                            'Follow any legitimate verification instructions provided by the platform.',
                        'status' => 'If needed',
                    ],

                    [
                        'title' => 'Monitor the response',
                        'description' =>
                            'Review responses and follow any additional official instructions.',
                        'status' => 'Ongoing',
                    ],
                ];

            @endphp


            <div class="mt-8">

                @foreach($steps as $index => $step)

                    <div class="relative flex gap-5">

                        {{-- Connecting line --}}

                        @if(!$loop->last)

                            <div
                                class="
                                    absolute
                                    left-[7px]
                                    top-5
                                    bottom-0
                                    w-px
                                    bg-gray-200
                                "
                            ></div>

                        @endif


                        {{-- Timeline marker --}}

                        <div class="relative z-10 shrink-0">

                            <div
                                class="
                                    flex
                                    h-4
                                    w-4
                                    items-center
                                    justify-center
                                    rounded-full

                                    {{ $index === 0
                                        ? 'bg-[#b04c4c] ring-4 ring-[#f5e9e9]'
                                        : 'border-2 border-[#777943] bg-white' }}
                                "
                            ></div>

                        </div>


                        {{-- Step content --}}

                        <div class="pb-10">

                            <div class="flex flex-wrap items-center gap-3">

                                <h3
                                    class="
                                        font-bold
                                        text-gray-950
                                    "
                                >
                                    {{ $step['title'] }}
                                </h3>


                                <span
                                    class="
                                        rounded
                                        bg-gray-100
                                        px-2
                                        py-1
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wide
                                        text-gray-500
                                    "
                                >
                                    {{ $step['status'] }}
                                </span>

                            </div>


                            <p
                                class="
                                    mt-2
                                    max-w-xl
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                {{ $step['description'] }}
                            </p>

                        </div>

                    </div>

                @endforeach

            </div>

        </section>


        {{-- ========================================================= --}}
        {{-- OFFICIAL RECOVERY OPTIONS --}}
        {{-- ========================================================= --}}

        <section class="document-section mb-12">

            <span class="document-marker document-marker-olive"></span>


            <div class="section-label">
                Official options
            </div>


            <h2
                class="
                    mt-5
                    text-2xl
                    font-bold
                    tracking-tight
                    text-gray-950
                "
            >
                Official recovery channels
            </h2>


            <p
                class="
                    mt-3
                    max-w-2xl
                    text-sm
                    leading-7
                    text-gray-500
                "
            >
                Use only official platform recovery and support channels
                when continuing with your case.
            </p>


            <div class="mt-7 space-y-4">

                {{-- Account recovery --}}

                <a
                    href="#"
                    class="
                        group
                        block
                        border
                        border-gray-200
                        bg-white
                        p-5
                        transition
                        hover:border-[#111d8f]
                        hover:bg-[#f8f8ff]
                    "
                >

                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-4
                        "
                    >

                        <div>

                            <div
                                class="
                                    text-sm
                                    font-bold
                                    text-gray-950
                                "
                            >
                                Account recovery
                            </div>


                            <div
                                class="
                                    mt-1
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                Open the platform's official recovery process.
                            </div>

                        </div>


                        <span
                            class="
                                text-lg
                                text-gray-400
                                transition
                                group-hover:text-[#111d8f]
                            "
                        >
                            →
                        </span>

                    </div>

                </a>


                {{-- Official support --}}

                <a
                    href="#"
                    class="
                        group
                        block
                        border
                        border-gray-200
                        bg-white
                        p-5
                        transition
                        hover:border-[#111d8f]
                        hover:bg-[#f8f8ff]
                    "
                >

                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-4
                        "
                    >

                        <div>

                            <div
                                class="
                                    text-sm
                                    font-bold
                                    text-gray-950
                                "
                            >
                                Official support
                            </div>


                            <div
                                class="
                                    mt-1
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                Use the platform's official support resources.
                            </div>

                        </div>


                        <span
                            class="
                                text-lg
                                text-gray-400
                                transition
                                group-hover:text-[#111d8f]
                            "
                        >
                            →
                        </span>

                    </div>

                </a>

            </div>

        </section>


        {{-- ========================================================= --}}
        {{-- SAFETY NOTICE --}}
        {{-- ========================================================= --}}

        <section
            class="
                mb-12
                border
                border-[#dedde8]
                bg-[#f7f6fc]
                p-6
            "
        >

            <div class="flex items-start gap-4">

                <div class="shrink-0 text-[#111d8f]">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-6 w-6"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.2 12.48A2 2 0 004.82 19h14.36a1.99 1.99 0 001.73-2.66l-7.2-12.48a2 2 0 00-3.46 0z"
                        />

                    </svg>

                </div>


                <div>

                    <div
                        class="
                            text-sm
                            font-bold
                            text-gray-950
                        "
                    >
                        Stay safe
                    </div>


                    <p
                        class="
                            mt-2
                            max-w-2xl
                            text-sm
                            leading-6
                            text-gray-500
                        "
                    >
                        ReclaimPilot does not need your platform password,
                        authentication codes, or private account credentials.
                        Never share those details with anyone claiming to
                        provide recovery assistance.
                    </p>

                </div>

            </div>

        </section>


        {{-- ========================================================= --}}
        {{-- CASE SUMMARY --}}
        {{-- ========================================================= --}}

        <section class="document-section">

            <span class="document-marker"></span>


            <div class="section-label">
                Case summary
            </div>


            <h2
                class="
                    mt-5
                    text-2xl
                    font-bold
                    tracking-tight
                    text-gray-950
                "
            >
                Recovery case details
            </h2>


            <div class="mt-7 max-w-2xl">

                {{-- Case ID --}}

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-4
                        border-b
                        border-gray-100
                        py-4
                    "
                >

                    <span class="text-sm text-gray-500">
                        Case ID
                    </span>


                    <span
                        class="
                            text-sm
                            font-semibold
                            text-gray-950
                        "
                    >
                        #{{ $case->id }}
                    </span>

                </div>


                {{-- Issue --}}

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-4
                        border-b
                        border-gray-100
                        py-4
                    "
                >

                    <span class="text-sm text-gray-500">
                        Issue
                    </span>


                    <span
                        class="
                            text-right
                            text-sm
                            font-semibold
                            text-gray-950
                        "
                    >
                        {{ ucwords(str_replace('_', ' ', $case->issue_type)) }}
                    </span>

                </div>


                {{-- Status --}}

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-4
                        py-4
                    "
                >

                    <span class="text-sm text-gray-500">
                        Status
                    </span>


                    <span
                        class="
                            text-sm
                            font-semibold
                            text-[#111d8f]
                        "
                    >
                        {{ ucfirst($case->status) }}
                    </span>

                </div>

            </div>

        </section>

    </x-case-shell>

@endsection