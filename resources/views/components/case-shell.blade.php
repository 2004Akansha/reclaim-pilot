@props([
    'currentStep' => 1,
    'case' => null,
])

@php

    $steps = [
        1 => [
            'label' => 'Diagnose',
            'description' => 'Describe your account issue',
        ],
        2 => [
            'label' => 'Analyze',
            'description' => 'Review the account problem',
        ],
        3 => [
            'label' => 'Prepare',
            'description' => 'Prepare recovery evidence',
        ],
        4 => [
            'label' => 'Escalate',
            'description' => 'Continue with recovery',
        ],
    ];

    $issueNames = [
        'account_disabled' => 'Account disabled',
        'hacked_account' => 'Hacked account',
        'cant_login' => "Can't log in",
        'other' => 'Other account issue',
    ];

    $issueName = $case
        ? ($issueNames[$case->issue_type] ?? 'Account issue')
        : 'New recovery case';

    $accountType = $case?->account_type
        ? ucfirst($case->account_type)
        : 'Not provided';

@endphp


<div class="case-shell">

    {{-- ========================================================= --}}
    {{-- LEFT PANEL --}}
    {{-- ========================================================= --}}

    <aside class="case-sidebar">

        {{-- Brand --}}

        <a
            href="{{ url('/') }}"
            class="flex items-center gap-3"
        >

            <div
                class="flex h-10 w-10 items-center justify-center
                       rounded-xl bg-[#111d8f] text-white"
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

                <div class="text-base font-bold tracking-tight text-gray-950">
                    ReclaimPilot
                </div>

                <div class="text-[11px] text-gray-500">
                    Account recovery assistant
                </div>

            </div>

        </a>


        {{-- Case information --}}

        <div class="mt-10">

            @if($case)

                <div class="text-[11px] font-bold uppercase tracking-[0.18em] text-gray-400">
                    Recovery case
                </div>

                <div class="mt-2 text-2xl font-bold text-gray-950">
                    #{{ $case->id }}
                </div>


                <div class="mt-5 rounded-xl border border-[#dedde8] bg-white/70 p-4">

                    <div class="text-[11px] font-bold uppercase tracking-[0.14em] text-gray-400">
                        Issue
                    </div>

                    <div class="mt-1 font-semibold text-gray-900">
                        {{ $issueName }}
                    </div>


                    <div class="mt-4 text-[11px] font-bold uppercase tracking-[0.14em] text-gray-400">
                        Account type
                    </div>

                    <div class="mt-1 text-sm font-medium text-gray-800">
                        {{ $accountType }}
                    </div>


                    @if($case->description)

                        <div class="mt-4 text-[11px] font-bold uppercase tracking-[0.14em] text-gray-400">
                            Description
                        </div>

                        <p class="mt-1 text-sm leading-6 text-gray-600">
                            {{ \Illuminate\Support\Str::limit($case->description, 130) }}
                        </p>

                    @endif

                </div>

            @else

                <div class="text-[11px] font-bold uppercase tracking-[0.18em] text-gray-400">
                    New recovery case
                </div>

                <h2 class="mt-2 text-2xl font-bold tracking-tight text-gray-950">
                    Let's get started
                </h2>

                <p class="mt-3 text-sm leading-6 text-gray-600">
                    Tell us what happened and we'll guide you through
                    the recovery process.
                </p>

            @endif

        </div>


        {{-- Journey --}}

        <div class="mt-10">

            <div class="text-[11px] font-bold uppercase tracking-[0.18em] text-gray-400">
                Recovery journey
            </div>


            <div class="mt-6">

                @foreach($steps as $number => $step)

                    @php

                        $isCurrent =
                            $number === $currentStep;

                        $isCompleted =
                            $number < $currentStep;

                        $routeUrl = null;

                        if ($number === 1) {
                            $routeUrl = route('case.create');
                        }

                        if ($number === 2 && $case) {
                            $routeUrl = route('case.analyze', $case);
                        }

                        if ($number === 3 && $case) {
                            $routeUrl = route('case.prepare', $case);
                        }

                        if ($number === 4 && $case) {
                            $routeUrl = route('case.escalate', $case);
                        }

                    @endphp


                    <div class="relative flex gap-4">

                        {{-- Connecting line --}}

                        @if(!$loop->last)

                            <div
                                class="
                                    absolute
                                    left-[7px]
                                    top-[18px]
                                    h-[calc(100%-2px)]
                                    w-px
                                    {{ $isCompleted
                                        ? 'bg-[#b04c4c]'
                                        : 'bg-[#cfcfd8]' }}
                                "
                            ></div>

                        @endif


                        {{-- Marker --}}

                        <div class="relative z-10 shrink-0">

                            @if($isCompleted)

                                <div
                                    class="
                                        flex h-4 w-4 items-center
                                        justify-center rounded-full
                                        bg-[#b04c4c]
                                        ring-4 ring-[#f7f6fc]
                                    "
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        class="h-2.5 w-2.5 text-white"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.704 5.29a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>

                                </div>

                            @elseif($isCurrent)

                                <div
                                    class="
                                        h-4 w-4 rounded-full
                                        bg-[#b04c4c]
                                        ring-4 ring-[#f1dede]
                                    "
                                ></div>

                            @else

                                <div
                                    class="
                                        h-4 w-4 rounded-full
                                        border-2 border-[#777943]
                                        bg-[#f7f6fc]
                                    "
                                ></div>

                            @endif

                        </div>


                        {{-- Text --}}

                        <div class="pb-8">

                            @if($routeUrl)

                                <a
                                    href="{{ $routeUrl }}"
                                    class="
                                        block
                                        transition
                                        {{ $isCurrent
                                            ? 'text-gray-950'
                                            : 'text-gray-500 hover:text-gray-900' }}
                                    "
                                >

                                    <div
                                        class="
                                            text-sm
                                            {{ $isCurrent
                                                ? 'font-bold'
                                                : 'font-semibold' }}
                                        "
                                    >
                                        {{ $step['label'] }}
                                    </div>

                                    <div class="mt-1 text-xs leading-5 text-gray-500">
                                        {{ $step['description'] }}
                                    </div>

                                </a>

                            @else

                                <div>

                                    <div class="text-sm font-semibold text-gray-500">
                                        {{ $step['label'] }}
                                    </div>

                                    <div class="mt-1 text-xs leading-5 text-gray-400">
                                        {{ $step['description'] }}
                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- Safety notice --}}

        <div class="mt-auto pt-8">

            <div class="rounded-xl border border-[#dedde8] bg-white/60 p-4">

                <div class="flex gap-3">

                    <div class="shrink-0 text-[#111d8f]">

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
                                d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.2 12.48A2 2 0 004.82 19h14.36a2 2 0 001.73-2.66l-7.2-12.48a2 2 0 00-3.46 0z"
                            />
                        </svg>

                    </div>

                    <div>

                        <div class="text-xs font-bold text-gray-900">
                            Stay safe
                        </div>

                        <p class="mt-1 text-xs leading-5 text-gray-500">
                            Never share passwords, authentication codes
                            or recovery codes.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </aside>


    {{-- ========================================================= --}}
    {{-- MAIN CONTENT --}}
    {{-- ========================================================= --}}

    <section class="case-main">

        <div class="mx-auto w-full max-w-4xl">

            {{ $slot }}

        </div>

    </section>

</div>