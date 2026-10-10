@extends('layouts.reclaimpilot')

@section('title', 'ReclaimPilot - Prepare')

@section('content')

    <x-stepper :current-step="3" />

    {{-- Success message --}}

    @if(session('success'))

        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4">

            <p class="text-sm font-medium text-emerald-800">

                {{ session('success') }}

            </p>

        </div>

    @endif

    @if(session('error'))

    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

        <p class="text-sm font-semibold text-red-800">
            Something went wrong
        </p>

        <p class="text-sm text-red-700 mt-1">
            {{ session('error') }}
        </p>

    </div>

@endif

        {{-- Validation errors --}}

    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

            <p class="font-semibold text-red-800">

                Please fix the following:

            </p>


            <ul class="mt-2 list-disc list-inside text-sm text-red-700">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Heading --}}

    <div class="mb-8">

        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">
            Prepare your recovery case
        </h1>

        <p class="mt-3 text-gray-500 max-w-2xl leading-7">
            Review your case, complete the evidence checklist,
            and prepare a clear appeal draft before using the
            official recovery process.
        </p>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1.4fr_0.8fr] gap-6">


        {{-- LEFT COLUMN --}}

        <div class="space-y-6">


            {{-- Identified issue --}}

            <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

                <div class="flex items-start gap-4">

                    <div
                        class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-xl"
                    >
                        ✓
                    </div>

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-emerald-700">
                            Identified issue
                        </div>

                        <h2 class="text-xl font-bold mt-1">

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

                            <p class="text-sm text-gray-500 mt-2">
                                {{ $case->possible_reason }}
                            </p>

                        @endif

                    </div>

                </div>

            </section>


{{-- Evidence Checklist --}}

<section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

    <div class="flex items-center justify-between gap-4">

        <div>

            <h2 class="text-lg font-semibold text-gray-900">
                Evidence checklist
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Upload supporting documents for your recovery case.
            </p>

        </div>


        @php

            $completedEvidence = $case->evidence
                ->where('completed', true)
                ->count();

            $totalEvidence = $case->evidence->count();

        @endphp


        <div class="text-sm font-semibold text-emerald-700">

            {{ $completedEvidence }}/{{ $totalEvidence }}

            complete

        </div>

    </div>


    <div class="mt-6 space-y-4">

        @forelse($case->evidence as $evidence)

            <div
                class="border border-gray-200 rounded-xl p-4"
            >

                <div class="flex items-start gap-4">


                    {{-- Status icon --}}

                    <div class="flex-shrink-0">

                        @if($evidence->completed)

                            <div
                                class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-semibold"
                            >
                                ✓
                            </div>

                        @else

                            <div
                                class="w-9 h-9 rounded-full border-2 border-gray-300 text-gray-400 flex items-center justify-center"
                            >
                                ○
                            </div>

                        @endif

                    </div>


                    {{-- Evidence content --}}

                    <div class="flex-1 min-w-0">

                        <h3 class="font-semibold text-gray-900">

                            {{ $evidence->title }}

                        </h3>


                        <p class="text-sm text-gray-500 mt-1">

                            {{ $evidence->description }}

                        </p>


                        {{-- Uploaded file information --}}

                        @if($evidence->file_path)

                            <div
                                class="mt-4 flex items-center justify-between gap-3 bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3"
                            >

                                <div class="min-w-0">

                                    <p
                                        class="text-sm font-medium text-emerald-800 truncate"
                                    >

                                        {{ $evidence->original_name }}

                                    </p>


                                    @if($evidence->file_size)

                                        <p class="text-xs text-emerald-600 mt-1">

                                            {{ number_format($evidence->file_size / 1024 / 1024, 2) }}
                                            MB

                                        </p>

                                    @endif

                                </div>


                                <a
                                    href="{{ asset('storage/' . $evidence->file_path) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-sm font-medium text-emerald-700 hover:text-emerald-900 whitespace-nowrap"
                                >
                                    View
                                </a>

                            </div>

                        @endif


                        {{-- Upload form --}}

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
                            class="mt-4"
                        >

                            @csrf


                            <div class="flex flex-col sm:flex-row gap-3">

                                <input
                                    type="file"
                                    name="file"
                                    accept=".jpg,.jpeg,.png,.webp,.pdf"
                                    required
                                    class="flex-1 text-sm text-gray-500
                                           file:mr-4
                                           file:py-2
                                           file:px-4
                                           file:rounded-lg
                                           file:border-0
                                           file:text-sm
                                           file:font-medium
                                           file:bg-emerald-50
                                           file:text-emerald-700
                                           hover:file:bg-emerald-100"
                                >


                                <button
                                    type="submit"
                                    class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition"
                                >

                                    @if($evidence->file_path)

                                        Replace file

                                    @else

                                        Upload

                                    @endif

                                </button>

                            </div>


                            <p class="text-xs text-gray-400 mt-2">

                                JPG, PNG, WEBP or PDF.
                                Maximum 10 MB.

                            </p>

                        </form>

                    </div>

                </div>

            </div>

        @empty

            <p class="py-4 text-sm text-gray-500">

                No evidence items have been added yet.

            </p>

        @endforelse

    </div>

</section>

            {{-- Appeal --}}

            <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

                <div class="flex items-start justify-between gap-4">

                    <div>

                        <h2 class="text-lg font-semibold">
                            Appeal draft
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Review and edit this draft before submitting it
                            through the official recovery process.
                        </p>

                    </div>

                    <span
                        class="text-xs font-semibold bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full"
                    >
                        Draft
                    </span>

                </div>


                @if($appeal)

                    <textarea
                        id="appealText"
                        class="mt-5 w-full min-h-[340px] rounded-xl border border-gray-200 bg-gray-50 px-4 py-4 text-sm leading-7 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 resize-y"
                    >{{ $appeal->content }}</textarea>


                    <div class="flex flex-wrap gap-3 mt-4">

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
                                class="px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm font-semibold text-gray-700"
                            >
                                Regenerate
                            </button>

                        </form>


                        <button
                            type="button"
                            onclick="copyAppeal()"
                            class="px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-sm font-semibold text-white"
                        >
                            Copy draft
                        </button>

                    </div>

                @else

                    <div class="mt-5 rounded-xl bg-gray-50 border border-gray-100 p-6 text-center">

                        <div class="text-3xl">
                            📝
                        </div>

                        <p class="font-medium mt-3">
                            No appeal draft yet
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Generate a draft based on your case information.
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
                                class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold"
                            >
                                Generate appeal
                            </button>

                        </form>

                    </div>

                @endif

            </section>

        </div>


        {{-- RIGHT COLUMN --}}

        <div class="space-y-6">


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
                            Account
                        </span>

                        <span class="text-sm font-semibold">
                            {{ $case->account_type
                                ? ucfirst($case->account_type)
                                : 'Not provided' }}
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


            {{-- Guidance --}}

            <section class="rounded-2xl border border-blue-100 bg-blue-50 p-6">

                <div class="flex gap-3">

                    <div class="text-xl">
                        💡
                    </div>

                    <div>

                        <h3 class="font-semibold text-gray-900">
                            Before submitting
                        </h3>

                        <p class="text-sm text-gray-600 leading-6 mt-2">
                            Review the appeal carefully. Only include information
                            that is accurate and relevant to your situation.
                        </p>

                    </div>

                </div>

            </section>


            {{-- Continue --}}

            <a
                href="{{ route('case.escalate', $case) }}"
                class="w-full inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 rounded-xl transition"
            >
                Continue to recovery options →
            </a>

        </div>

    </div>

@endsection


@push('scripts')

<script>

function copyAppeal()
{
    const textarea =
        document.getElementById('appealText');

    if (!textarea) {
        return;
    }

    navigator.clipboard.writeText(textarea.value)
        .then(function () {

            alert('Appeal copied to clipboard.');

        })
        .catch(function () {

            textarea.select();

            document.execCommand('copy');

            alert('Appeal copied to clipboard.');

        });
}

</script>

@endpush