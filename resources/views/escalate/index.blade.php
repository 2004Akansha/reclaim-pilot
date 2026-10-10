@extends('layouts.reclaimpilot')

@section('title', 'ReclaimPilot - Recovery Options')

@section('content')

    <x-stepper :current-step="4" />


    {{-- Heading --}}

    <div class="mb-8">

        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">
            Take the next step
        </h1>

        <p class="mt-3 text-gray-500 max-w-2xl leading-7">
            Your case is prepared. Review your appeal and use the
            platform's official recovery and support options.
        </p>

    </div>


    <div class="grid grid-cols-1 lg:grid-cols-[1.4fr_0.8fr] gap-6">


        {{-- LEFT --}}

        <div class="space-y-6">


            {{-- Recommended action --}}

            <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

                <h2 class="text-lg font-semibold mb-5">
                    Recommended next step
                </h2>

                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

                    <div class="flex items-start gap-4">

                        <div
                            class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-xl shrink-0"
                        >
                            ✓
                        </div>

                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-emerald-700">
                                Recommended
                            </div>

                            <h3 class="text-xl font-bold mt-1">
                                {{ $recommendation['title'] }}
                            </h3>

                            <p class="text-sm text-gray-600 leading-6 mt-2">
                                {{ $recommendation['description'] }}
                            </p>

                        </div>

                    </div>

                    @php
    $buttonText = match ($recommendation['action']) {
        'submit_appeal' =>
            'Review & submit appeal →',

        'secure_account' =>
            'Review security steps →',

        'recover_account' =>
            'Review recovery steps →',

        'provide_more_information' =>
            'Provide more information →',

        default =>
            'Review case →',
    };
@endphp

<a
    href="{{ route('case.prepare', $case) }}"
    class="mt-5 inline-flex items-center justify-center px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition"
>
    {{ $buttonText }}
</a>

                </div>

            </section>


            {{-- Recovery path --}}

            <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

                <h2 class="text-lg font-semibold">
                    Your recovery path
                </h2>


                <div class="mt-6 space-y-0">

                    @php

                        $steps = [
                            [
                                'title' => 'Review your appeal',
                                'description' => 'Check that your explanation and account details are accurate.',
                                'status' => 'Do now',
                            ],
                            [
                                'title' => 'Use the official recovery process',
                                'description' => 'Submit your request through the platform’s official support or recovery channel.',
                                'status' => 'Next',
                            ],
                            [
                                'title' => 'Provide verification if requested',
                                'description' => 'Follow any legitimate verification instructions provided by the platform.',
                                'status' => 'If needed',
                            ],
                            [
                                'title' => 'Monitor the response',
                                'description' => 'Review responses and follow any additional official instructions.',
                                'status' => 'Ongoing',
                            ],
                        ];

                    @endphp


                    @foreach($steps as $index => $step)

                        <div class="flex gap-4">

                            <div class="flex flex-col items-center">

                                <div
                                    class="
                                        w-9 h-9 rounded-full flex items-center justify-center
                                        font-semibold text-sm shrink-0
                                        {{ $index === 0
                                            ? 'bg-emerald-600 text-white'
                                            : 'bg-gray-100 text-gray-600' }}
                                    "
                                >
                                    {{ $index + 1 }}
                                </div>

                                @if(!$loop->last)

                                    <div class="w-px h-16 bg-gray-200"></div>

                                @endif

                            </div>


                            <div class="pb-7">

                                <div class="flex items-center gap-2 flex-wrap">

                                    <h3 class="font-semibold">
                                        {{ $step['title'] }}
                                    </h3>

                                    <span
                                        class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600"
                                    >
                                        {{ $step['status'] }}
                                    </span>

                                </div>

                                <p class="text-sm text-gray-500 leading-6 mt-1">
                                    {{ $step['description'] }}
                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        </div>


        {{-- RIGHT --}}

        <div class="space-y-6">


            {{-- Official recovery options --}}

            <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

                <h2 class="text-lg font-semibold">
                    Official recovery options
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Use only official platform channels.
                </p>


                <div class="mt-5 space-y-3">


                    {{-- Placeholder for official link --}}

                    <a
                        href="#"
                        class="block p-4 rounded-xl border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50/40 transition"
                    >

                        <div class="flex items-center justify-between">

                            <div>

                                <div class="font-semibold text-sm">
                                    Account recovery
                                </div>

                                <div class="text-xs text-gray-500 mt-1">
                                    Open the platform's official recovery process.
                                </div>

                            </div>

                            <span class="text-gray-400">
                                →
                            </span>

                        </div>

                    </a>


                    <a
                        href="#"
                        class="block p-4 rounded-xl border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50/40 transition"
                    >

                        <div class="flex items-center justify-between">

                            <div>

                                <div class="font-semibold text-sm">
                                    Official support
                                </div>

                                <div class="text-xs text-gray-500 mt-1">
                                    Use the platform's official support resources.
                                </div>

                            </div>

                            <span class="text-gray-400">
                                →
                            </span>

                        </div>

                    </a>


                </div>

            </section>


            {{-- Safety information --}}

            <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6">

                <div class="flex items-start gap-3">

                    <div class="text-xl">
                        ⚠️
                    </div>

                    <div>

                        <h3 class="font-semibold text-gray-900">
                            Stay safe
                        </h3>

                        <p class="text-sm text-gray-600 leading-6 mt-2">
                            ReclaimPilot does not need your platform password,
                            authentication codes, or private account credentials.
                            Never share those details with anyone claiming to
                            provide recovery assistance.
                        </p>

                    </div>

                </div>

            </section>


            {{-- Case summary --}}

            <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

                <h2 class="text-lg font-semibold">
                    Case summary
                </h2>


                <div class="mt-4 space-y-3">

                    <div class="flex justify-between gap-4 py-2 border-b border-gray-100">

                        <span class="text-sm text-gray-500">
                            Case ID
                        </span>

                        <span class="text-sm font-semibold">
                            #{{ $case->id }}
                        </span>

                    </div>


                    <div class="flex justify-between gap-4 py-2 border-b border-gray-100">

                        <span class="text-sm text-gray-500">
                            Issue
                        </span>

                        <span class="text-sm font-semibold text-right">
                            {{ ucwords(str_replace('_', ' ', $case->issue_type)) }}
                        </span>

                    </div>


                    <div class="flex justify-between gap-4 py-2">

                        <span class="text-sm text-gray-500">
                            Status
                        </span>

                        <span class="text-sm font-semibold text-emerald-700">
                            {{ ucfirst($case->status) }}
                        </span>

                    </div>

                </div>

            </section>

        </div>

    </div>

@endsection