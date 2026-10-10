@extends('layouts.reclaimpilot')

@section('title', 'ReclaimPilot - Analyze')

@section('content')

    <x-stepper :current-step="2" />

     @if(session('error'))

        <div
            class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4"
        >

            <p class="font-semibold text-red-800">
                Analysis failed
            </p>

            <p class="text-sm text-red-700 mt-1">
                {{ session('error') }}
            </p>

        </div>

    @endif

    {{-- Heading --}}

    <div class="mb-8">

        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">
            Analyze your case
        </h1>

        <p class="mt-3 text-gray-500 max-w-2xl leading-7">
            We'll review the information you provided and help identify
            the most appropriate recovery guidance.
        </p>

    </div>


    {{-- Case information --}}

    <div class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow mb-6">

        <h2 class="text-lg font-semibold mb-5">
            Your case
        </h2>


        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="bg-gray-50 rounded-xl p-4">

                <div class="text-xs text-gray-500 mb-1">
                    Issue
                </div>

                <div class="font-semibold">
                    {{ ucwords(str_replace('_', ' ', $case->issue_type)) }}
                </div>

            </div>


            <div class="bg-gray-50 rounded-xl p-4">

                <div class="text-xs text-gray-500 mb-1">
                    Account type
                </div>

                <div class="font-semibold">
                    {{ $case->account_type
                        ? ucfirst($case->account_type)
                        : 'Not provided' }}
                </div>

            </div>

        </div>


        @if($case->description)

            <div class="bg-gray-50 rounded-xl p-4 mt-4">

                <div class="text-xs text-gray-500 mb-1">
                    Description
                </div>

                <div class="text-sm leading-6">
                    {{ $case->description }}
                </div>

            </div>

        @endif

    </div>

    {{-- Screenshot evidence --}}

<div class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow mb-6">

    <div class="flex items-start justify-between gap-4">

        <div>

            <h2 class="text-lg font-semibold">
                Screenshot evidence
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Uploaded screenshots are analyzed by Vision AI
                and used as supporting evidence for the final case analysis.
            </p>

        </div>

        @if($case->screenshots->count())

            <span
                class="text-xs font-semibold bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full"
            >
                {{ $case->screenshots->count() }}
                {{ $case->screenshots->count() === 1 ? 'file' : 'files' }}
            </span>

        @endif

    </div>


    @if($case->screenshots->count())

        <div class="mt-5 space-y-4">

            @foreach($case->screenshots as $screenshot)

                <div
                    class="rounded-xl border border-gray-200 p-4"
                >

                    <div class="flex items-center justify-between gap-4">

                        <div class="font-medium text-gray-900">
                            {{ $screenshot->original_name }}
                        </div>

                        @if($screenshot->analysis)

                            <span
                                class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full"
                            >
                                Analyzed
                            </span>

                        @else

                            <span
                                class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full"
                            >
                                Not analyzed
                            </span>

                        @endif

                    </div>


                    @if($screenshot->analysis)

                        <div class="mt-4 space-y-3 text-sm">

                            <div>

                                <div class="text-xs text-gray-500">
                                    Detected issue
                                </div>

                                <div class="font-semibold mt-1">
                                    {{ $screenshot->analysis->detected_issue }}
                                </div>

                            </div>


                            <div>

                                <div class="text-xs text-gray-500">
                                    Visible message
                                </div>

                                <div class="mt-1">
                                    {{ $screenshot->analysis->visible_message ?: 'Not detected' }}
                                </div>

                            </div>


                            <div>

                                <div class="text-xs text-gray-500">
                                    Possible reason
                                </div>

                                <div class="mt-1">
                                    {{ $screenshot->analysis->possible_reason ?: 'Not determined' }}
                                </div>

                            </div>


                            <div>

                                <div class="text-xs text-gray-500">
                                    Vision confidence
                                </div>

                                <div class="font-semibold mt-1">
                                    {{ $screenshot->analysis->confidence }}%
                                </div>

                            </div>

                        </div>

                    @else

                        <p class="mt-3 text-sm text-gray-500">
                            This screenshot will be analyzed when you start
                            the complete case analysis.
                        </p>

                    @endif

                </div>

            @endforeach

        </div>

    @else

        <div
            class="mt-5 bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm text-gray-500"
        >
            No screenshots were uploaded for this case.
            The text analysis will still use the information you provided.
        </div>

    @endif

</div>

    {{-- Analysis Progress --}}

<div
    id="analysis-page"

    data-status-url="{{ route('case.analysis.status', $case) }}"

    data-prepare-url="{{ route('case.prepare', $case) }}"
>

    {{-- Analysis progress card --}}

    <div
        id="analysis-progress-card"
        class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow mb-6
        {{ $case->status === 'analyzing' ? '' : 'hidden' }}"
    >

        <div class="mb-6">

            <h2 class="text-xl font-semibold">
                AI is analyzing your case
            </h2>

            <p class="text-sm text-gray-500 mt-2">
                We're reviewing your inputs, screenshots, and account
                information to understand the issue and find the best
                recovery options.
            </p>

        </div>


        {{-- Step 1 --}}

        <div
            class="analysis-step flex items-center gap-4 py-4"
            data-step="reading_inputs"
        >

            <div
                class="step-icon w-10 h-10 rounded-full
                bg-gray-100 text-gray-500
                flex items-center justify-center shrink-0"
            >
                ○
            </div>

            <div class="flex-1">

                <h3 class="font-semibold">
                    Reading your inputs
                </h3>

                <p class="text-sm text-gray-500">
                    Analyzing the details you provided
                </p>

            </div>

            <div class="step-percent text-sm text-gray-500">
                0%
            </div>

        </div>


        {{-- Step 2 --}}

        <div
            class="analysis-step flex items-center gap-4 py-4"
            data-step="analyzing_screenshots"
        >

            <div
                class="step-icon w-10 h-10 rounded-full
                bg-gray-100 text-gray-500
                flex items-center justify-center shrink-0"
            >
                ○
            </div>

            <div class="flex-1">

                <h3 class="font-semibold">
                    Analyzing screenshots
                </h3>

                <p class="text-sm text-gray-500">
                    Extracting useful information from images
                </p>

            </div>

            <div class="step-percent text-sm text-gray-500">
                0%
            </div>

        </div>


        {{-- Step 3 --}}

        <div
            class="analysis-step flex items-center gap-4 py-4"
            data-step="identifying_issue"
        >

            <div
                class="step-icon w-10 h-10 rounded-full
                bg-gray-100 text-gray-500
                flex items-center justify-center shrink-0"
            >
                ○
            </div>

            <div class="flex-1">

                <h3 class="font-semibold">
                    Identifying issue type
                </h3>

                <p class="text-sm text-gray-500">
                    Classifying your case using AI
                </p>

            </div>

            <div class="step-percent text-sm text-gray-500">
                0%
            </div>

        </div>


        {{-- Step 4 --}}

        <div
            class="analysis-step flex items-center gap-4 py-4"
            data-step="checking_policies"
        >

            <div
                class="step-icon w-10 h-10 rounded-full
                bg-gray-100 text-gray-500
                flex items-center justify-center shrink-0"
            >
                ○
            </div>

            <div class="flex-1">

                <h3 class="font-semibold">
                    Checking relevant policies
                </h3>

                <p class="text-sm text-gray-500">
                    Reviewing relevant recovery information
                </p>

            </div>

            <div class="step-percent text-sm text-gray-500">
                0%
            </div>

        </div>


        {{-- Step 5 --}}

        <div
            class="analysis-step flex items-center gap-4 py-4"
            data-step="finding_similar_cases"
        >

            <div
                class="step-icon w-10 h-10 rounded-full
                bg-gray-100 text-gray-500
                flex items-center justify-center shrink-0"
            >
                ○
            </div>

            <div class="flex-1">

                <h3 class="font-semibold">
                    Finding similar cases
                </h3>

                <p class="text-sm text-gray-500">
                    Looking at available recovery patterns
                </p>

            </div>

            <div class="step-percent text-sm text-gray-500">
                0%
            </div>

        </div>


        {{-- Progress --}}

        <div class="mt-6">

            <div class="flex justify-between mb-2">

                <span class="text-sm text-gray-600">
                    Analysis progress
                </span>

                <span
                    id="progress-text"
                    class="text-sm font-semibold"
                >
                    {{ $case->analysis_progress ?? 0 }}%
                </span>

            </div>


            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">

                <div
                    id="progress-bar"
                    class="h-2 bg-emerald-600 rounded-full transition-all duration-500"
                    style="width: {{ $case->analysis_progress ?? 0 }}%"
                ></div>

            </div>

        </div>

    </div>


    {{-- Failed --}}

    <div
        id="analysis-error"
        class="bg-red-50 border border-red-200
        rounded-2xl p-6 mb-6
        {{ $case->status === 'analysis_failed' ? '' : 'hidden' }}"
    >

        <h2 class="font-semibold text-red-800">
            Analysis failed
        </h2>

        <p class="text-sm text-red-700 mt-2">
            We could not analyze your case right now.
            Please try again.
        </p>


        <form
            method="POST"
            action="{{ route('case.analyze.process', $case) }}"
            class="mt-4"
        >

            @csrf

            <button
                type="submit"
                class="bg-red-600 hover:bg-red-700
                text-white font-semibold
                px-5 py-3 rounded-xl"
            >
                Try again
            </button>

        </form>

    </div>


    {{-- Start analysis --}}

    <div
        id="start-analysis-card"
        class="bg-white border border-gray-200
        rounded-2xl p-6 card-shadow mb-6
        {{ in_array($case->status, ['analyzing', 'analyzed'])
            ? 'hidden'
            : '' }}"
    >

        <h2 class="text-lg font-semibold">
            Ready to analyze your case?
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            We'll review your information and uploaded evidence
            to determine the most appropriate recovery path.
        </p>


        <form
            method="POST"
            action="{{ route('case.analyze.process', $case) }}"
            class="mt-5"
        >

            @csrf

            <button
                type="submit"
                class="w-full bg-emerald-600
                hover:bg-emerald-700
                text-white font-semibold
                py-4 rounded-xl transition"
            >
                Start analysis
            </button>

        </form>

    </div>


    {{-- Analysis Result --}}

    <div
        id="analysis-result-card"
        class="bg-white border border-emerald-200
        rounded-2xl p-6 card-shadow
        {{ $analysis ? '' : 'hidden' }}"
    >

        @if($analysis)

            <div class="flex items-center justify-between gap-4">

                <div>

                    <h2 class="text-lg font-semibold">
                        Analysis result
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        This is guidance based on the information provided.
                    </p>

                </div>

                <div class="text-sm font-semibold text-emerald-700">
                    {{ $analysis->confidence }}% confidence
                </div>

            </div>


            <div class="mt-5 space-y-4">

                <div>

                    <div class="text-xs text-gray-500">
                        Issue detected
                    </div>

                    <div class="font-semibold mt-1">
                        {{ $analysis->issue_detected }}
                    </div>

                </div>


                <div>

                    <div class="text-xs text-gray-500">
                        Possible reason
                    </div>

                    <div class="font-semibold mt-1">
                        {{ $analysis->possible_reason ?: 'Not determined' }}
                    </div>

                </div>


                <div class="bg-gray-50 rounded-xl p-4">

                    <div class="text-xs text-gray-500 mb-1">
                        Summary
                    </div>

                    <p class="text-sm leading-6">
                        {{ $analysis->summary }}
                    </p>

                </div>

            </div>


            <a
                href="{{ route('case.prepare', $case) }}"
                class="mt-6 w-full inline-flex items-center
                justify-center bg-emerald-600
                hover:bg-emerald-700 text-white
                font-semibold py-3.5 rounded-xl transition"
            >
                Continue to preparation →
            </a>

        @endif

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const page = document.getElementById('analysis-page');

    if (!page) {
        return;
    }


    const statusUrl =
        page.dataset.statusUrl;

    const prepareUrl =
        page.dataset.prepareUrl;


    const progressCard =
        document.getElementById(
            'analysis-progress-card'
        );

    const startCard =
        document.getElementById(
            'start-analysis-card'
        );

    const resultCard =
        document.getElementById(
            'analysis-result-card'
        );

    const errorBox =
        document.getElementById(
            'analysis-error'
        );

    const progressBar =
        document.getElementById(
            'progress-bar'
        );

    const progressText =
        document.getElementById(
            'progress-text'
        );


    const steps = [
        'reading_inputs',
        'analyzing_screenshots',
        'identifying_issue',
        'checking_policies',
        'finding_similar_cases'
    ];


    /*
    |--------------------------------------------------------------------------
    | Update progress UI
    |--------------------------------------------------------------------------
    */

    function updateProgress(data) {

        const progress =
            Number(data.progress || 0);

        const currentStep =
            data.step;


        /*
        |--------------------------------------------------------------------------
        | Progress bar
        |--------------------------------------------------------------------------
        */

        if (progressBar) {

            progressBar.style.width =
                progress + '%';

        }


        if (progressText) {

            progressText.textContent =
                progress + '%';

        }


        /*
        |--------------------------------------------------------------------------
        | Step indicators
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.analysis-step')
            .forEach(function (element) {

                const step =
                    element.dataset.step;

                const icon =
                    element.querySelector(
                        '.step-icon'
                    );

                const percentage =
                    element.querySelector(
                        '.step-percent'
                    );


                const currentIndex =
                    steps.indexOf(currentStep);

                const stepIndex =
                    steps.indexOf(step);


                /*
                |--------------------------------------------------------------------------
                | Completed step
                |--------------------------------------------------------------------------
                */

                if (
                    currentIndex !== -1 &&
                    stepIndex < currentIndex
                ) {

                    icon.textContent = '✓';

                    icon.className =
                        'step-icon w-10 h-10 rounded-full ' +
                        'bg-emerald-100 text-emerald-700 ' +
                        'flex items-center justify-center shrink-0';

                    percentage.textContent =
                        'Done';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Current step
                |--------------------------------------------------------------------------
                */

                if (step === currentStep) {

                    icon.textContent = '◉';

                    icon.className =
                        'step-icon w-10 h-10 rounded-full ' +
                        'bg-emerald-100 text-emerald-700 ' +
                        'flex items-center justify-center shrink-0';

                    percentage.textContent =
                        progress + '%';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Waiting step
                |--------------------------------------------------------------------------
                */

                icon.textContent = '○';

                icon.className =
                    'step-icon w-10 h-10 rounded-full ' +
                    'bg-gray-100 text-gray-500 ' +
                    'flex items-center justify-center shrink-0';

                percentage.textContent =
                    '0%';

            });

    }


    /*
    |--------------------------------------------------------------------------
    | Check Laravel for latest status
    |--------------------------------------------------------------------------
    */

    async function checkStatus() {

        try {

            const response =
                await fetch(statusUrl, {

                    method: 'GET',

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }

                });


            if (!response.ok) {

                throw new Error(
                    'Unable to retrieve analysis status.'
                );

            }


            const data =
                await response.json();


            console.log(
                'Analysis status:',
                data
            );


            /*
            |--------------------------------------------------------------------------
            | Update progress
            |--------------------------------------------------------------------------
            */

            updateProgress(data);


            /*
            |--------------------------------------------------------------------------
            | Still analyzing
            |--------------------------------------------------------------------------
            */

            if (data.status === 'analyzing') {

                progressCard.classList.remove(
                    'hidden'
                );

                startCard.classList.add(
                    'hidden'
                );

                /*
                | Check again after 1.5 seconds.
                */

                setTimeout(
                    checkStatus,
                    1500
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Analysis completed
            |--------------------------------------------------------------------------
            */

            if (data.status === 'analyzed') {

                progressBar.style.width =
                    '100%';

                progressText.textContent =
                    '100%';


                /*
                | Give the user a small visual completion
                | moment before opening Prepare.
                */

                setTimeout(function () {

                    window.location.href =
                        prepareUrl;

                }, 700);

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Analysis failed
            |--------------------------------------------------------------------------
            */

            if (data.status === 'analysis_failed') {

                progressCard.classList.add(
                    'hidden'
                );

                startCard.classList.add(
                    'hidden'
                );

                errorBox.classList.remove(
                    'hidden'
                );

                return;
            }


        } catch (error) {

            console.error(
                'Analysis polling error:',
                error
            );


            /*
            | Don't immediately show failure.
            |
            | The queue may still be working.
            */

            setTimeout(
                checkStatus,
                3000
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Start polling automatically
    |--------------------------------------------------------------------------
    */

    if (
        @json($case->status === 'analyzing')
    ) {

        checkStatus();

    }

});

</script>

@endsection