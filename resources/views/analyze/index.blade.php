@extends('layouts.reclaimpilot')

@section('title', 'ReclaimPilot - Analyze')

@section('content')

<x-case-shell
    :current-step="2"
    :case="$case"
>

    {{-- ========================================================= --}}
    {{-- SESSION ERROR --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div
            class="
                mb-10
                border-l-4
                border-[#b04c4c]
                bg-[#fbf5f5]
                px-5
                py-5
            "
        >

            <p class="font-semibold text-red-800">
                Analysis failed
            </p>

            <p class="mt-1 text-sm text-red-700">
                {{ session('error') }}
            </p>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- PAGE HEADING --}}
    {{-- ========================================================= --}}

    <div class="mb-12">

        <span class="section-label">
            Step 02 · Analysis
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
            Analyze your case
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
            We'll review the information you provided and help identify
            the most appropriate recovery guidance.
        </p>

    </div>


    {{-- ========================================================= --}}
    {{-- YOUR CASE --}}
    {{-- ========================================================= --}}

    <section class="document-section mb-12">

        <span class="document-marker"></span>

        <div class="section-label">
            Your case
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
            Case information
        </h2>


        <div
            class="
                mt-6
                grid
                grid-cols-1
                gap-x-10
                gap-y-6
                sm:grid-cols-2
            "
        >

            {{-- Issue --}}

            <div>

                <div
                    class="
                        text-xs
                        font-bold
                        uppercase
                        tracking-wide
                        text-gray-400
                    "
                >
                    Issue
                </div>

                <div
                    class="
                        mt-2
                        text-base
                        font-semibold
                        text-gray-900
                    "
                >
                    {{ ucwords(str_replace('_', ' ', $case->issue_type)) }}
                </div>

            </div>


            {{-- Account type --}}

            <div>

                <div
                    class="
                        text-xs
                        font-bold
                        uppercase
                        tracking-wide
                        text-gray-400
                    "
                >
                    Account type
                </div>

                <div
                    class="
                        mt-2
                        text-base
                        font-semibold
                        text-gray-900
                    "
                >
                    {{ $case->account_type
                        ? ucfirst($case->account_type)
                        : 'Not provided' }}
                </div>

            </div>

        </div>


        {{-- Description --}}

        @if($case->description)

            <div
                class="
                    mt-7
                    border-t
                    border-gray-200
                    pt-6
                "
            >

                <div
                    class="
                        text-xs
                        font-bold
                        uppercase
                        tracking-wide
                        text-gray-400
                    "
                >
                    Description
                </div>

                <p
                    class="
                        mt-2
                        text-sm
                        leading-7
                        text-gray-700
                    "
                >
                    {{ $case->description }}
                </p>

            </div>

        @endif

    </section>


    {{-- ========================================================= --}}
    {{-- SCREENSHOT EVIDENCE --}}
    {{-- ========================================================= --}}

    <section class="document-section mb-12">

        <span class="document-marker document-marker-olive"></span>

        <div class="section-label">
            Supporting evidence
        </div>


        <div
            class="
                mt-5
                flex
                items-center
                justify-between
                gap-4
            "
        >

            <h2
                class="
                    text-2xl
                    font-bold
                    tracking-tight
                    text-gray-950
                "
            >
                Screenshot evidence
            </h2>


            @if($case->screenshots->count())

                <span
                    class="
                        text-xs
                        font-semibold
                        text-gray-500
                    "
                >
                    {{ $case->screenshots->count() }}

                    {{ $case->screenshots->count() === 1
                        ? 'file'
                        : 'files' }}
                </span>

            @endif

        </div>


        <p
            class="
                mt-2
                text-sm
                leading-6
                text-gray-500
            "
        >
            Uploaded screenshots are analyzed by Vision AI and used as
            supporting evidence for the final case analysis.
        </p>


        {{-- Screenshots exist --}}

        @if($case->screenshots->count())

            <div class="mt-7 space-y-6">

                @foreach($case->screenshots as $screenshot)

                    <div
                        class="
                            border-b
                            border-gray-200
                            pb-6
                            last:border-b-0
                            last:pb-0
                        "
                    >

                        {{-- Screenshot header --}}

                        <div
                            class="
                                flex
                                items-center
                                justify-between
                                gap-4
                            "
                        >

                            <div
                                class="
                                    min-w-0
                                    font-medium
                                    text-gray-900
                                "
                            >
                                {{ $screenshot->original_name }}
                            </div>


                            @if($screenshot->analysis)

                                <span
                                    class="
                                        shrink-0
                                        rounded-full
                                        bg-[#eef4e8]
                                        px-3
                                        py-1
                                        text-xs
                                        font-bold
                                        text-[#59632f]
                                    "
                                >
                                    Analyzed
                                </span>

                            @else

                                <span
                                    class="
                                        shrink-0
                                        rounded-full
                                        bg-gray-100
                                        px-3
                                        py-1
                                        text-xs
                                        font-bold
                                        text-gray-500
                                    "
                                >
                                    Not analyzed
                                </span>

                            @endif

                        </div>


                        {{-- Screenshot analysis --}}

                        @if($screenshot->analysis)

                            <div
                                class="
                                    mt-5
                                    grid
                                    grid-cols-1
                                    gap-x-10
                                    gap-y-5
                                    sm:grid-cols-2
                                "
                            >

                                {{-- Detected issue --}}

                                <div>

                                    <div
                                        class="
                                            text-xs
                                            font-bold
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        "
                                    >
                                        Detected issue
                                    </div>

                                    <div
                                        class="
                                            mt-2
                                            text-sm
                                            font-semibold
                                            text-gray-900
                                        "
                                    >
                                        {{ $screenshot->analysis->detected_issue }}
                                    </div>

                                </div>


                                {{-- Vision confidence --}}

                                <div>

                                    <div
                                        class="
                                            text-xs
                                            font-bold
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        "
                                    >
                                        Vision confidence
                                    </div>

                                    <div
                                        class="
                                            mt-2
                                            text-sm
                                            font-semibold
                                            text-gray-900
                                        "
                                    >
                                        {{ $screenshot->analysis->confidence }}%
                                    </div>

                                </div>


                                {{-- Visible message --}}

                                <div class="sm:col-span-2">

                                    <div
                                        class="
                                            text-xs
                                            font-bold
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        "
                                    >
                                        Visible message
                                    </div>

                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            leading-7
                                            text-gray-700
                                        "
                                    >
                                        {{ $screenshot->analysis->visible_message ?: 'Not detected' }}
                                    </p>

                                </div>


                                {{-- Possible reason --}}

                                <div class="sm:col-span-2">

                                    <div
                                        class="
                                            text-xs
                                            font-bold
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        "
                                    >
                                        Possible reason
                                    </div>

                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            leading-7
                                            text-gray-700
                                        "
                                    >
                                        {{ $screenshot->analysis->possible_reason ?: 'Not determined' }}
                                    </p>

                                </div>

                            </div>

                        @else

                            <p
                                class="
                                    mt-3
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                This screenshot will be analyzed when you
                                start the complete case analysis.
                            </p>

                        @endif

                    </div>

                @endforeach

            </div>

        @else

            <div
                class="
                    mt-6
                    border-l-4
                    border-[#777943]
                    bg-[#f7f7ef]
                    px-5
                    py-4
                "
            >

                <p class="text-sm leading-6 text-gray-600">
                    No screenshots were uploaded for this case.
                    The text analysis will still use the information
                    you provided.
                </p>

            </div>

        @endif

    </section>


    {{-- ========================================================= --}}
    {{-- ANALYSIS PAGE / POLLING --}}
    {{-- ========================================================= --}}

    <div
        id="analysis-page"
        data-status-url="{{ route('case.analysis.status', $case) }}"
    >


        {{-- ===================================================== --}}
        {{-- ANALYSIS PROGRESS --}}
        {{-- ===================================================== --}}

        <section
            id="analysis-progress-card"
            class="
                document-section
                mb-12
                {{ $case->status === 'analyzing'
                    ? ''
                    : 'hidden' }}
            "
        >

            <span class="document-marker"></span>

            <div class="section-label">
                Live analysis
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
                AI is analyzing your case
            </h2>

            <p
                class="
                    mt-2
                    max-w-2xl
                    text-sm
                    leading-7
                    text-gray-500
                "
            >
                We're reviewing your inputs, screenshots, and account
                information to understand the issue and find the best
                recovery options.
            </p>


            {{-- Analysis steps --}}

            <div class="mt-7 divide-y divide-gray-200">


                {{-- Step 1 --}}

                <div
                    class="
                        analysis-step
                        flex
                        items-center
                        gap-4
                        py-5
                    "
                    data-step="reading_inputs"
                >

                    <div
                        class="
                            step-icon
                            flex
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-gray-100
                            text-gray-500
                        "
                    >
                        ○
                    </div>

                    <div class="flex-1">

                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-900
                            "
                        >
                            Reading your inputs
                        </h3>

                        <p
                            class="
                                mt-1
                                text-xs
                                leading-5
                                text-gray-500
                            "
                        >
                            Analyzing the details you provided
                        </p>

                    </div>

                    <div
                        class="
                            step-percent
                            text-xs
                            font-semibold
                            text-gray-500
                        "
                    >
                        0%
                    </div>

                </div>


                {{-- Step 2 --}}

                <div
                    class="
                        analysis-step
                        flex
                        items-center
                        gap-4
                        py-5
                    "
                    data-step="analyzing_screenshots"
                >

                    <div
                        class="
                            step-icon
                            flex
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-gray-100
                            text-gray-500
                        "
                    >
                        ○
                    </div>

                    <div class="flex-1">

                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-900
                            "
                        >
                            Analyzing screenshots
                        </h3>

                        <p
                            class="
                                mt-1
                                text-xs
                                leading-5
                                text-gray-500
                            "
                        >
                            Extracting useful information from images
                        </p>

                    </div>

                    <div
                        class="
                            step-percent
                            text-xs
                            font-semibold
                            text-gray-500
                        "
                    >
                        0%
                    </div>

                </div>


                {{-- Step 3 --}}

                <div
                    class="
                        analysis-step
                        flex
                        items-center
                        gap-4
                        py-5
                    "
                    data-step="identifying_issue"
                >

                    <div
                        class="
                            step-icon
                            flex
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-gray-100
                            text-gray-500
                        "
                    >
                        ○
                    </div>

                    <div class="flex-1">

                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-900
                            "
                        >
                            Analyzing your recovery issue
                        </h3>

                        <p
                            class="
                                mt-1
                                text-xs
                                leading-5
                                text-gray-500
                            "
                        >
                            Using AI to understand the case
                        </p>

                    </div>

                    <div
                        class="
                            step-percent
                            text-xs
                            font-semibold
                            text-gray-500
                        "
                    >
                        0%
                    </div>

                </div>


                {{-- Step 4 --}}

                <div
                    class="
                        analysis-step
                        flex
                        items-center
                        gap-4
                        py-5
                    "
                    data-step="generating_guidance"
                >

                    <div
                        class="
                            step-icon
                            flex
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-gray-100
                            text-gray-500
                        "
                    >
                        ○
                    </div>

                    <div class="flex-1">

                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-900
                            "
                        >
                            Generating recovery guidance
                        </h3>

                        <p
                            class="
                                mt-1
                                text-xs
                                leading-5
                                text-gray-500
                            "
                        >
                            Creating personalized next steps
                        </p>

                    </div>

                    <div
                        class="
                            step-percent
                            text-xs
                            font-semibold
                            text-gray-500
                        "
                    >
                        0%
                    </div>

                </div>


                {{-- Step 5 --}}

                <div
                    class="
                        analysis-step
                        flex
                        items-center
                        gap-4
                        py-5
                    "
                    data-step="finalizing"
                >

                    <div
                        class="
                            step-icon
                            flex
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-gray-100
                            text-gray-500
                        "
                    >
                        ○
                    </div>

                    <div class="flex-1">

                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-900
                            "
                        >
                            Finalizing recommendations
                        </h3>

                        <p
                            class="
                                mt-1
                                text-xs
                                leading-5
                                text-gray-500
                            "
                        >
                            Preparing your recovery plan
                        </p>

                    </div>

                    <div
                        class="
                            step-percent
                            text-xs
                            font-semibold
                            text-gray-500
                        "
                    >
                        0%
                    </div>

                </div>

            </div>


            {{-- Progress --}}

            <div class="mt-7">

                <div class="mb-2 flex justify-between">

                    <span
                        class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-wide
                            text-gray-500
                        "
                    >
                        Analysis progress
                    </span>

                    <span
                        id="progress-text"
                        class="
                            text-xs
                            font-bold
                            text-gray-900
                        "
                    >
                        {{ $case->analysis_progress ?? 0 }}%
                    </span>

                </div>


                <div
                    class="
                        h-2
                        w-full
                        overflow-hidden
                        rounded-full
                        bg-gray-200
                    "
                >

                    <div
                        id="progress-bar"
                        class="
                            h-2
                            rounded-full
                            bg-[#111d8f]
                            transition-all
                            duration-500
                        "
                        style="width: {{ $case->analysis_progress ?? 0 }}%"
                    ></div>

                </div>

            </div>

        </section>


        {{-- ===================================================== --}}
        {{-- ANALYSIS ERROR --}}
        {{-- ===================================================== --}}

        <section
            id="analysis-error"
            class="
                document-section
                mb-12
                {{ $case->status === 'analysis_failed'
                    ? ''
                    : 'hidden' }}
            "
        >

            <span class="document-marker"></span>

            <div class="section-label">
                Analysis error
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
                Analysis failed
            </h2>

            <p
                class="
                    mt-2
                    text-sm
                    leading-7
                    text-gray-600
                "
            >
                We could not analyze your case right now.
                Please try again.
            </p>


            <form
                method="POST"
                action="{{ route('case.analyze.process', $case) }}"
                class="mt-6"
            >

                @csrf

                <button
                    type="submit"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        bg-[#111d8f]
                        px-5
                        py-3
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-[#0d176f]
                    "
                >
                    Try again
                </button>

            </form>

        </section>


        {{-- ===================================================== --}}
        {{-- START ANALYSIS --}}
        {{-- ===================================================== --}}

        <section
            id="start-analysis-card"
            class="
                document-section
                mb-12
                {{ in_array($case->status, ['analyzing', 'analyzed'])
                    ? 'hidden'
                    : '' }}
            "
        >

            <span class="document-marker"></span>

            <div class="section-label">
                Ready to begin
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
                Ready to analyze your case?
            </h2>

            <p
                class="
                    mt-2
                    max-w-2xl
                    text-sm
                    leading-7
                    text-gray-600
                "
            >
                We'll review your information and uploaded evidence
                to determine the most appropriate recovery path.
            </p>


            <form
                method="POST"
                action="{{ route('case.analyze.process', $case) }}"
                class="mt-6"
            >

                @csrf

                <button
                    type="submit"
                    class="
                        inline-flex
                        w-full
                        items-center
                        justify-center
                        bg-[#111d8f]
                        px-6
                        py-4
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-[#0d176f]
                    "
                >
                    Start analysis
                </button>

            </form>

        </section>


        {{-- ===================================================== --}}
        {{-- ANALYSIS RESULT --}}
        {{-- ===================================================== --}}

        <section
            id="analysis-result-card"
            class="
                document-section
                mb-12
                {{ $analysis ? '' : 'hidden' }}
            "
        >

            <span class="document-marker"></span>

            @if($analysis)

                <div class="section-label">
                    Analysis result
                </div>


                <div
                    class="
                        mt-5
                        flex
                        flex-wrap
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div>

                        <h2
                            class="
                                text-2xl
                                font-bold
                                tracking-tight
                                text-gray-950
                            "
                        >
                            What we found
                        </h2>

                        <p
                            class="
                                mt-2
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            This is guidance based on the information
                            provided.
                        </p>

                    </div>


                    <div
                        class="
                            rounded-full
                            bg-[#f3e8a4]
                            px-3
                            py-1.5
                            text-xs
                            font-bold
                            text-[#4d4d29]
                        "
                    >
                        {{ $analysis->confidence }}% confidence
                    </div>

                </div>


                {{-- Analysis information --}}

                <div class="mt-8 space-y-7">

                    {{-- Issue detected --}}

                    <div>

                        <div
                            class="
                                text-xs
                                font-bold
                                uppercase
                                tracking-wide
                                text-gray-400
                            "
                        >
                            Issue detected
                        </div>

                        <div
                            class="
                                mt-2
                                text-lg
                                font-semibold
                                text-gray-950
                            "
                        >
                            {{ $analysis->issue_detected }}
                        </div>

                    </div>


                    {{-- Possible reason --}}

                    <div>

                        <div
                            class="
                                text-xs
                                font-bold
                                uppercase
                                tracking-wide
                                text-gray-400
                            "
                        >
                            Possible reason
                        </div>

                        <p
                            class="
                                mt-2
                                text-sm
                                leading-7
                                text-gray-700
                            "
                        >
                            {{ $analysis->possible_reason ?: 'Not determined' }}
                        </p>

                    </div>


                    {{-- Summary --}}

                    <div>

                        <div
                            class="
                                text-xs
                                font-bold
                                uppercase
                                tracking-wide
                                text-gray-400
                            "
                        >
                            Summary
                        </div>

                        <p
                            class="
                                mt-2
                                text-sm
                                leading-7
                                text-gray-700
                            "
                        >
                            {{ $analysis->summary }}
                        </p>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- GUIDANCE --}}
                {{-- ================================================= --}}

                @if($analysis && $guidance)

                    <section class="document-section mt-12">

                        <span class="document-marker document-marker-olive"></span>

                        <div class="section-label">
                            Recovery guidance
                        </div>


                        <div
                            class="
                                mt-5
                                flex
                                flex-wrap
                                items-center
                                gap-3
                            "
                        >

                            <h2
                                class="
                                    text-2xl
                                    font-bold
                                    tracking-tight
                                    text-gray-950
                                "
                            >
                                {{ $guidance['title'] }}
                            </h2>


                            {{-- AI badge --}}

                            @if($analysis->guidance_source === 'ai')

                                <span
                                    class="
                                        rounded-full
                                        bg-[#eef0ff]
                                        px-3
                                        py-1
                                        text-xs
                                        font-bold
                                        text-[#111d8f]
                                    "
                                >
                                    AI Generated
                                </span>


                            {{-- Rule-based badge --}}

                            @elseif($analysis->guidance_source === 'rule_based')

                                <span
                                    class="
                                        rounded-full
                                        bg-gray-100
                                        px-3
                                        py-1
                                        text-xs
                                        font-bold
                                        text-gray-600
                                    "
                                >
                                    Rule-Based Fallback
                                </span>

                            @endif

                        </div>


                        {{-- Guidance summary --}}

                        <p
                            class="
                                mt-3
                                text-sm
                                leading-7
                                text-gray-600
                            "
                        >
                            {{ $guidance['summary'] }}
                        </p>


                        {{-- ================================================= --}}
                        {{-- STEPS --}}
                        {{-- ================================================= --}}

                        <div class="mt-8">

                            <h3
                                class="
                                    text-sm
                                    font-bold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                "
                            >
                                What you should do
                            </h3>


                            <div class="mt-5 space-y-6">

                                @foreach($guidance['steps'] as $index => $step)

                                    <div class="flex gap-4">

                                        <div
                                            class="
                                                flex
                                                h-8
                                                w-8
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-[#111d8f]
                                                text-xs
                                                font-bold
                                                text-white
                                            "
                                        >
                                            {{ $index + 1 }}
                                        </div>

                                        <p
                                            class="
                                                pt-1
                                                text-sm
                                                leading-7
                                                text-gray-700
                                            "
                                        >
                                            {{ $step }}
                                        </p>

                                    </div>

                                @endforeach

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- EVIDENCE --}}
                        {{-- ================================================= --}}

                        <div
                            class="
                                mt-10
                                border-t
                                border-gray-200
                                pt-8
                            "
                        >

                            <h3
                                class="
                                    text-sm
                                    font-bold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                "
                            >
                                Evidence you may need
                            </h3>


                            <div class="mt-5 space-y-3">

                                @foreach($guidance['evidence'] as $item)

                                    <div
                                        class="
                                            flex
                                            items-start
                                            gap-3
                                        "
                                    >

                                        <span
                                            class="
                                                mt-1
                                                flex
                                                h-4
                                                w-4
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-[#777943]
                                                text-[9px]
                                                text-white
                                            "
                                        >
                                            ✓
                                        </span>

                                        <span
                                            class="
                                                text-sm
                                                leading-6
                                                text-gray-700
                                            "
                                        >
                                            {{ $item }}
                                        </span>

                                    </div>

                                @endforeach

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- WARNINGS --}}
                        {{-- ================================================= --}}

                        <div
                            class="
                                mt-10
                                border-l-4
                                border-[#b04c4c]
                                bg-[#fbf5f5]
                                px-5
                                py-5
                            "
                        >

                            <h3
                                class="
                                    text-sm
                                    font-bold
                                    text-gray-900
                                "
                            >
                                Things to avoid
                            </h3>


                            <div class="mt-3 space-y-2">

                                @foreach($guidance['avoid'] as $item)

                                    <p
                                        class="
                                            text-sm
                                            leading-6
                                            text-gray-600
                                        "
                                    >
                                        • {{ $item }}
                                    </p>

                                @endforeach

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- HOW TO USE GUIDANCE --}}
                        {{-- ================================================= --}}

                        <div
                            class="
                                mt-10
                                border-t
                                border-gray-200
                                pt-8
                            "
                        >

                            <h3
                                class="
                                    text-sm
                                    font-bold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                "
                            >
                                How to use this guidance
                            </h3>


                            <div
                                class="
                                    mt-4
                                    space-y-3
                                    text-sm
                                    leading-6
                                    text-gray-600
                                "
                            >

                                <p>

                                    <strong class="text-gray-900">
                                        1.
                                    </strong>

                                    Read the recommended action and
                                    understand why it was suggested.

                                </p>


                                <p>

                                    <strong class="text-gray-900">
                                        2.
                                    </strong>

                                    Follow the recovery steps in the
                                    order shown above.

                                </p>


                                <p>

                                    <strong class="text-gray-900">
                                        3.
                                    </strong>

                                    Prepare the evidence that may be
                                    required.

                                </p>


                                <p>

                                    <strong class="text-gray-900">
                                        4.
                                    </strong>

                                    Do not share passwords, recovery
                                    codes, or other sensitive credentials.

                                </p>


                                <p>

                                    <strong class="text-gray-900">
                                        5.
                                    </strong>

                                    Continue to the next step when
                                    you are ready.

                                </p>

                            </div>

                        </div>

                    </section>

                @endif


                {{-- ================================================= --}}
                {{-- NEXT STEPS --}}
                {{-- ================================================= --}}

                <section class="document-section mt-12">

                    <span class="document-marker document-marker-olive"></span>

                    <div class="section-label">
                        Next steps
                    </div>


                    <div class="mt-5">

                        <h2
                            class="
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
                                text-sm
                                leading-7
                                text-gray-600
                            "
                        >
                            {{ $recommendation['description'] }}
                        </p>

                    </div>


                    {{-- Action button --}}

                    @if(
                        $recommendation['action'] === 'submit_appeal'
                        ||
                        $recommendation['action'] === 'secure_account'
                        ||
                        $recommendation['action'] === 'recover_account'
                        ||
                        $recommendation['action'] === 'provide_more_information'
                    )

                        <a
                            href="{{ route('case.prepare', $case) }}"
                            class="
                                mt-6
                                inline-flex
                                w-full
                                items-center
                                justify-center
                                bg-[#111d8f]
                                px-6
                                py-4
                                text-sm
                                font-semibold
                                text-white
                                transition
                                hover:bg-[#0d176f]
                            "
                        >
                            {{ $recommendation['button'] }}
                        </a>

                    @endif

                </section>

            @endif

        </section>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- IMPORTANT: DO NOT REMOVE THIS POLLING LOGIC --}}
    {{-- ========================================================= --}}

    <script>

    document.addEventListener('DOMContentLoaded', function () {

        const page =
            document.getElementById('analysis-page');

        if (!page) {
            return;
        }


        const statusUrl =
            page.dataset.statusUrl;


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

            'generating_guidance',

            'finalizing'

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
            | Completed
            |--------------------------------------------------------------------------
            */

            if (currentStep === 'completed') {

                document
                    .querySelectorAll('.analysis-step')
                    .forEach(function (element) {

                        const icon =
                            element.querySelector(
                                '.step-icon'
                            );


                        const percentage =
                            element.querySelector(
                                '.step-percent'
                            );


                        icon.textContent = '✓';


                        icon.className =
                            'step-icon flex h-9 w-9 ' +
                            'shrink-0 items-center ' +
                            'justify-center rounded-full ' +
                            'bg-emerald-100 ' +
                            'text-emerald-700';


                        percentage.textContent =
                            'Done';

                    });


                return;
            }


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
                        steps.indexOf(
                            currentStep
                        );


                    const stepIndex =
                        steps.indexOf(
                            step
                        );


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
                            'step-icon flex h-9 w-9 ' +
                            'shrink-0 items-center ' +
                            'justify-center rounded-full ' +
                            'bg-emerald-100 ' +
                            'text-emerald-700';


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
                            'step-icon flex h-9 w-9 ' +
                            'shrink-0 items-center ' +
                            'justify-center rounded-full ' +
                            'bg-[#f3e8a4] ' +
                            'text-[#4d4d29]';


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
                        'step-icon flex h-9 w-9 ' +
                        'shrink-0 items-center ' +
                        'justify-center rounded-full ' +
                        'bg-gray-100 ' +
                        'text-gray-500';


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
                    await fetch(
                        statusUrl,
                        {
                            method: 'GET',

                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }
                        }
                    );


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
                    |--------------------------------------------------------------------------
                    | Check again after 1.5 seconds
                    |--------------------------------------------------------------------------
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

                    if (progressBar) {

                        progressBar.style.width =
                            '100%';

                    }


                    if (progressText) {

                        progressText.textContent =
                            '100%';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Reload Analyze page
                    |--------------------------------------------------------------------------
                    |
                    | Laravel will reload:
                    |
                    | $analysis
                    | $recommendation
                    | $guidance
                    |
                    | and display the completed result.
                    |
                    */

                    setTimeout(function () {

                        window.location.reload();

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
                |--------------------------------------------------------------------------
                | Don't immediately show failure.
                |
                | The queue may still be working.
                |--------------------------------------------------------------------------
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

</x-case-shell>

@endsection