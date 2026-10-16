@extends('layouts.reclaimpilot')

@section('title', 'ReclaimPilot - Prepare')

@section('content')

    {{-- ========================================================= --}}
    {{-- CASE SHELL --}}
    {{-- ========================================================= --}}

    <x-case-shell
        :current-step="3"
        :case="$case"
    >

        {{-- ===================================================== --}}
        {{-- FLASH MESSAGES --}}
        {{-- ===================================================== --}}

        @if(session('success'))

            <div
                class="
                    mb-8
                    border
                    border-[#dfe2c8]
                    bg-[#f7f7ec]
                    px-5
                    py-4
                "
            >

                <p class="text-sm font-medium text-[#5d6032]">
                    {{ session('success') }}
                </p>

            </div>

        @endif


        @if(session('error'))

            <div
                class="
                    mb-8
                    border
                    border-red-200
                    bg-red-50
                    px-5
                    py-4
                "
            >

                <p class="text-sm font-semibold text-red-800">
                    Something went wrong
                </p>

                <p class="mt-1 text-sm text-red-700">
                    {{ session('error') }}
                </p>

            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ===================================================== --}}

        @if($errors->any())

            <div
                class="
                    mb-8
                    border
                    border-red-200
                    bg-red-50
                    px-5
                    py-4
                "
            >

                <p class="font-semibold text-red-800">
                    Please fix the following:
                </p>


                <ul
                    class="
                        mt-2
                        list-inside
                        list-disc
                        text-sm
                        text-red-700
                    "
                >

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- PAGE HEADING --}}
        {{-- ===================================================== --}}

        <div class="mb-12">

            <span class="section-label">
                Step 03 · Preparation
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
                Prepare your recovery case
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
                Review your case, complete the evidence checklist,
                and prepare a clear appeal before using the official
                recovery process.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- IDENTIFIED ISSUE --}}
        {{-- ===================================================== --}}

        <section class="document-section mb-12">

            <span class="document-marker"></span>


            <div class="section-label">
                Identified issue
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

                @switch($case->issue_type)

                    @case('account_disabled')
                        Account disabled
                        @break

                    @case('hacked_account')
                        Hacked account
                        @break

                    @case('cant_login')
                        Can't log in
                        @break

                    @case('other')
                        Other account issue
                        @break

                    @default
                        Account issue

                @endswitch

            </h2>


            @if($case->possible_reason)

                <p
                    class="
                        mt-3
                        max-w-2xl
                        text-sm
                        leading-6
                        text-gray-500
                    "
                >
                    {{ $case->possible_reason }}
                </p>

            @endif


            {{-- Case details --}}

            <div
                class="
                    mt-6
                    grid
                    grid-cols-1
                    gap-3
                    sm:grid-cols-2
                "
            >

                <div
                    class="
                        border
                        border-gray-200
                        bg-[#fafafa]
                        px-4
                        py-3
                    "
                >

                    <div
                        class="
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-[0.14em]
                            text-gray-400
                        "
                    >
                        Case ID
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900">
                        #{{ $case->id }}
                    </div>

                </div>


                <div
                    class="
                        border
                        border-gray-200
                        bg-[#fafafa]
                        px-4
                        py-3
                    "
                >

                    <div
                        class="
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-[0.14em]
                            text-gray-400
                        "
                    >
                        Account type
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $case->account_type
                            ? ucfirst($case->account_type)
                            : 'Not provided' }}
                    </div>

                </div>

            </div>


            @if($case->description)

                <div class="mt-5">

                    <div
                        class="
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-[0.14em]
                            text-gray-400
                        "
                    >
                        Your description
                    </div>


                    <p
                        class="
                            mt-2
                            max-w-2xl
                            text-sm
                            leading-7
                            text-gray-600
                        "
                    >
                        {{ $case->description }}
                    </p>

                </div>

            @endif

        </section>


        {{-- ===================================================== --}}
        {{-- EVIDENCE --}}
        {{-- ===================================================== --}}

        @php

            $completedEvidence = $case->evidence
                ->where('completed', true)
                ->count();

            $totalEvidence = $case->evidence->count();

        @endphp


        <section class="document-section mb-12">

            <span class="document-marker"></span>


            <div class="flex items-start justify-between gap-6">

                <div>

                    <div class="section-label">
                        Evidence
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
                        Evidence checklist
                    </h2>


                    <p
                        class="
                            mt-2
                            max-w-2xl
                            text-sm
                            leading-6
                            text-gray-500
                        "
                    >
                        Upload supporting documents for your recovery case.
                        Strong, relevant evidence can make your request clearer.
                    </p>

                </div>


                <div
                    class="
                        shrink-0
                        text-sm
                        font-semibold
                        text-[#777943]
                    "
                >
                    {{ $completedEvidence }}/{{ $totalEvidence }}
                    complete
                </div>

            </div>


            {{-- ================================================= --}}
            {{-- EVIDENCE TIMELINE --}}
            {{-- ================================================= --}}

            <div class="mt-8">

                @forelse($case->evidence as $evidence)

                    <div class="relative flex gap-5">

                        {{-- Vertical timeline line --}}

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

                            @if($evidence->completed)

                                <div
                                    class="
                                        flex
                                        h-4
                                        w-4
                                        items-center
                                        justify-center
                                        rounded-full
                                        bg-[#777943]
                                        ring-4
                                        ring-white
                                    "
                                >

                                    <span class="text-[9px] text-white">
                                        ✓
                                    </span>

                                </div>

                            @else

                                <div
                                    class="
                                        h-4
                                        w-4
                                        rounded-full
                                        border-2
                                        border-[#b04c4c]
                                        bg-white
                                    "
                                ></div>

                            @endif

                        </div>


                        {{-- Evidence content --}}

                        <div class="min-w-0 flex-1 pb-10">

                            <h3
                                class="
                                    text-base
                                    font-bold
                                    text-gray-950
                                "
                            >
                                {{ $evidence->title }}
                            </h3>


                            <p
                                class="
                                    mt-2
                                    max-w-xl
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                {{ $evidence->description }}
                            </p>


                            {{-- ================================= --}}
                            {{-- Uploaded file --}}
                            {{-- ================================= --}}

                            @if($evidence->file_path)

                                <div
                                    class="
                                        mt-5
                                        flex
                                        items-center
                                        justify-between
                                        gap-4
                                        border
                                        border-[#dfe2c8]
                                        bg-[#f7f7ec]
                                        px-4
                                        py-3
                                    "
                                >

                                    <div class="min-w-0">

                                        <p
                                            class="
                                                truncate
                                                text-sm
                                                font-medium
                                                text-[#5d6032]
                                            "
                                        >
                                            {{ $evidence->original_name }}
                                        </p>


                                        @if($evidence->file_size)

                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-[#777943]
                                                "
                                            >
                                                {{ number_format($evidence->file_size / 1024 / 1024, 2) }}
                                                MB
                                            </p>

                                        @endif

                                    </div>


                                    <a
                                        href="{{ asset('storage/' . $evidence->file_path) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="
                                            shrink-0
                                            text-sm
                                            font-semibold
                                            text-[#111d8f]
                                            hover:underline
                                        "
                                    >
                                        View
                                    </a>

                                </div>

                            @endif


                            {{-- ================================= --}}
                            {{-- Upload form --}}
                            {{-- ================================= --}}

                            <form
                                method="POST"
                                action="{{ route(
                                    'case.evidence.upload',
                                    [
                                        'case' => $case,
                                        'evidence' => $evidence
                                    ]
                                ) }}"
                                enctype="multipart/form-data"
                                class="mt-5"
                            >

                                @csrf


                                <div
                                    class="
                                        flex
                                        flex-col
                                        gap-3
                                        sm:flex-row
                                        sm:items-center
                                    "
                                >

                                    <input
                                        type="file"
                                        name="file"
                                        accept=".jpg,.jpeg,.png,.webp,.pdf"
                                        required
                                        class="
                                            min-w-0
                                            flex-1
                                            text-sm
                                            text-gray-500

                                            file:mr-4
                                            file:rounded-md
                                            file:border-0
                                            file:bg-gray-100
                                            file:px-4
                                            file:py-2
                                            file:text-sm
                                            file:font-semibold
                                            file:text-gray-700

                                            hover:file:bg-gray-200
                                        "
                                    >


                                    <button
                                        type="submit"
                                        class="
                                            shrink-0
                                            border
                                            border-[#111d8f]
                                            bg-[#111d8f]
                                            px-5
                                            py-2.5
                                            text-sm
                                            font-semibold
                                            text-white
                                            transition
                                            hover:bg-[#0b146d]
                                        "
                                    >

                                        @if($evidence->file_path)

                                            Replace file

                                        @else

                                            Upload

                                        @endif

                                    </button>

                                </div>


                                <p
                                    class="
                                        mt-2
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    JPG, PNG, WEBP or PDF.
                                    Maximum 10 MB.
                                </p>

                            </form>

                        </div>

                    </div>

                @empty

                    <p class="py-4 text-sm text-gray-500">
                        No evidence items have been added yet.
                    </p>

                @endforelse

            </div>

        </section>


        {{-- ===================================================== --}}
        {{-- APPEAL --}}
        {{-- ===================================================== --}}

        <section class="document-section mb-12">

            <span class="document-marker"></span>


            <div class="section-label">
                Appeal
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
                Appeal draft
            </h2>


            <p
                class="
                    mt-2
                    max-w-2xl
                    text-sm
                    leading-6
                    text-gray-500
                "
            >
                Review and edit this draft before submitting it through
                the official recovery process.
            </p>


            @if($appeal)

                {{-- ============================================= --}}
                {{-- Appeal document --}}
                {{-- ============================================= --}}

                <div class="mt-6">

                    <textarea
                        id="appealText"
                        class="
                            min-h-[360px]
                            w-full
                            resize-y
                            border
                            border-gray-200
                            bg-[#fafafa]
                            p-6
                            text-sm
                            leading-7
                            text-gray-700
                            outline-none
                            transition
                            focus:border-[#111d8f]
                            focus:ring-1
                            focus:ring-[#111d8f]
                        "
                    >{{ $appeal->content }}</textarea>


                    <div class="mt-4 flex flex-wrap gap-3">

                        {{-- Regenerate --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'case.appeal.generate',
                                $case
                            ) }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="
                                    border
                                    border-gray-300
                                    bg-white
                                    px-5
                                    py-2.5
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    transition
                                    hover:bg-gray-50
                                "
                            >
                                Regenerate
                            </button>

                        </form>


                        {{-- Copy --}}

                        <button
                            type="button"
                            onclick="copyAppeal()"
                            class="
                                bg-[#111d8f]
                                px-5
                                py-2.5
                                text-sm
                                font-semibold
                                text-white
                                transition
                                hover:bg-[#0b146d]
                            "
                        >
                            Copy draft
                        </button>

                    </div>

                </div>

            @else

                {{-- ============================================= --}}
                {{-- No appeal yet --}}
                {{-- ============================================= --}}

                <div class="mt-6 border border-gray-200 bg-[#fafafa] p-7">

                    <div
                        class="
                            flex
                            h-10
                            w-10
                            items-center
                            justify-center
                            border
                            border-gray-200
                            bg-white
                            text-lg
                        "
                    >
                        ✎
                    </div>


                    <p
                        class="
                            mt-5
                            text-base
                            font-bold
                            text-gray-950
                        "
                    >
                        No appeal draft yet
                    </p>


                    <p
                        class="
                            mt-2
                            max-w-xl
                            text-sm
                            leading-6
                            text-gray-500
                        "
                    >
                        Generate a draft based on your case information.
                        You can review and edit it before submitting it.
                    </p>


                    <form
                        method="POST"
                        action="{{ route(
                            'case.appeal.generate',
                            $case
                        ) }}"
                        class="mt-5"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="
                                bg-[#111d8f]
                                px-5
                                py-2.5
                                text-sm
                                font-semibold
                                text-white
                                transition
                                hover:bg-[#0b146d]
                            "
                        >
                            Generate appeal
                        </button>

                    </form>

                </div>

            @endif

        </section>


        {{-- ===================================================== --}}
        {{-- BEFORE SUBMITTING --}}
        {{-- ===================================================== --}}

        <section
            class="
                mb-10
                border-t
                border-gray-200
                pt-8
            "
        >

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
                        bg-[#f3e8a4]
                        text-[#4d4d29]
                    "
                >
                    !
                </div>


                <div>

                    <h3
                        class="
                            text-sm
                            font-bold
                            text-gray-950
                        "
                    >
                        Before submitting
                    </h3>


                    <p
                        class="
                            mt-2
                            max-w-2xl
                            text-sm
                            leading-6
                            text-gray-500
                        "
                    >
                        Review the appeal carefully. Only include information
                        that is accurate and relevant to your situation.
                        Never include passwords, authentication codes,
                        or recovery codes.
                    </p>

                </div>

            </div>

        </section>


        {{-- ===================================================== --}}
        {{-- CONTINUE --}}
        {{-- ===================================================== --}}

        <div
            class="
                flex
                flex-col
                gap-4
                border-t
                border-gray-200
                pt-8
                sm:flex-row
                sm:items-center
                sm:justify-between
            "
        >

            <div>

                <div
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.14em]
                        text-gray-400
                    "
                >
                    Next step
                </div>


                <p
                    class="
                        mt-1
                        text-sm
                        font-semibold
                        text-gray-900
                    "
                >
                    Continue to recovery options
                </p>

            </div>


            <a
                href="{{ route('case.escalate', $case) }}"
                class="
                    inline-flex
                    items-center
                    justify-center
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
                Continue to recovery options →
            </a>

        </div>

    </x-case-shell>

@endsection


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

@push('scripts')

<script>

function copyAppeal()
{
    const textarea =
        document.getElementById('appealText');


    if (!textarea) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Modern clipboard API
    |--------------------------------------------------------------------------
    */

    if (navigator.clipboard) {

        navigator.clipboard
            .writeText(textarea.value)

            .then(function () {

                alert('Appeal copied to clipboard.');

            })

            .catch(function () {

                fallbackCopy(textarea);

            });

        return;
    }


    fallbackCopy(textarea);
}


function fallbackCopy(textarea)
{
    textarea.focus();

    textarea.select();

    textarea.setSelectionRange(
        0,
        textarea.value.length
    );


    try {

        document.execCommand('copy');

        alert('Appeal copied to clipboard.');

    } catch (error) {

        alert(
            'Unable to copy automatically. Please copy the draft manually.'
        );

    }
}

</script>

@endpush