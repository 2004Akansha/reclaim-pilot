@extends('layouts.reclaimpilot')

@section('title', 'ReclaimPilot - Diagnose')

@section('content')

<x-case-shell
    :current-step="1"
>


    {{-- ========================================================= --}}
    {{-- PAGE INTRO --}}
    {{-- ========================================================= --}}

    <div class="mb-10">

        <span class="section-label">
            Step 01 · Diagnose
        </span>


        <h1
            class="
                mt-5
                text-3xl
                font-bold
                tracking-tight
                text-gray-950
                sm:text-4xl
            "
        >
            Let's understand your case
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
            Share a few details about what happened.
            The more information you provide, the better we can help
            you prepare your recovery request.
        </p>

    </div>



    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div
            class="
                mb-10
                rounded-xl
                border
                border-red-200
                bg-red-50
                p-5
            "
        >

            <div class="flex gap-3">

                <div class="shrink-0 text-red-600">

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

                    <p class="font-semibold text-red-800">
                        Please fix the following:
                    </p>


                    <ul
                        class="
                            mt-2
                            list-disc
                            space-y-1
                            pl-5
                            text-sm
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

            </div>

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- MAIN FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route('case.store') }}"
        enctype="multipart/form-data"
    >

        @csrf



        {{-- ===================================================== --}}
        {{-- ISSUE TYPE --}}
        {{-- ===================================================== --}}

        <section class="document-section mb-12">

            <span class="document-marker"></span>


            <div class="section-label">
                Account problem
            </div>


            <h2
                class="
                    mt-4
                    text-2xl
                    font-bold
                    tracking-tight
                    text-gray-950
                "
            >
                What seems to be the issue?
            </h2>


            <p class="mt-2 text-sm leading-6 text-gray-500">
                Select the option that best describes your problem.
            </p>



            @php

                $issues = [

                    [
                        'value' => 'account_disabled',
                        'title' => 'Account disabled',
                        'description' => 'My account has been disabled',
                        'icon' => '🔒',
                    ],

                    [
                        'value' => 'hacked_account',
                        'title' => 'Hacked account',
                        'description' => 'Someone accessed my account',
                        'icon' => '🛡️',
                    ],

                    [
                        'value' => 'cant_login',
                        'title' => "Can't log in",
                        'description' => 'I cannot access my account',
                        'icon' => '🔑',
                    ],

                    [
                        'value' => 'other',
                        'title' => 'Other issue',
                        'description' => 'Something else is wrong',
                        'icon' => '⚠️',
                    ],

                ];

            @endphp



            <div
                class="
                    mt-6
                    grid
                    grid-cols-1
                    gap-3
                    sm:grid-cols-2
                "
            >

                @foreach($issues as $issue)

                    <label class="cursor-pointer">

                        <input
                            type="radio"
                            name="issue_type"
                            value="{{ $issue['value'] }}"
                            class="peer sr-only"
                            {{ old('issue_type') === $issue['value'] ? 'checked' : '' }}
                            required
                        >


                        <div
                            class="
                                rounded-xl
                                border
                                border-gray-200
                                bg-white
                                p-5
                                transition

                                hover:border-[#111d8f]
                                hover:bg-[#fafaff]

                                peer-checked:border-[#111d8f]
                                peer-checked:bg-[#f5f5ff]

                                peer-checked:ring-1
                                peer-checked:ring-[#111d8f]
                            "
                        >

                            <div class="flex items-start gap-4">


                                {{-- Icon --}}

                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-lg
                                        bg-[#f1f1f8]
                                        text-lg
                                    "
                                >
                                    {{ $issue['icon'] }}
                                </div>


                                {{-- Text --}}

                                <div>

                                    <div
                                        class="
                                            font-semibold
                                            text-gray-950
                                        "
                                    >
                                        {{ $issue['title'] }}
                                    </div>


                                    <div
                                        class="
                                            mt-1
                                            text-sm
                                            leading-5
                                            text-gray-500
                                        "
                                    >
                                        {{ $issue['description'] }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </label>

                @endforeach

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- DESCRIPTION --}}
        {{-- ===================================================== --}}

        <section class="document-section mb-12">

            <span class="document-marker document-marker-olive"></span>


            <div class="section-label">
                Account details
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
                    Tell us more
                </h2>


                <span
                    id="descriptionCounter"
                    class="
                        whitespace-nowrap
                        text-xs
                        font-medium
                        text-gray-400
                    "
                >
                    0/500
                </span>

            </div>


            <p class="mt-2 text-sm leading-6 text-gray-500">
                Describe what happened in your own words.
            </p>


            <textarea
                id="description"
                name="description"
                rows="6"
                maxlength="500"
                placeholder="Example: My account was disabled after a notification appeared, but I believe this was a mistake..."
                class="
                    mt-5
                    w-full
                    resize-none
                    border-0
                    border-b
                    border-gray-300
                    bg-transparent
                    px-0
                    py-3
                    text-sm
                    leading-7
                    outline-none
                    placeholder:text-gray-300
                    focus:border-[#111d8f]
                    focus:ring-0
                "
            >{{ old('description') }}</textarea>

        </section>



        {{-- ===================================================== --}}
        {{-- SCREENSHOTS --}}
        {{-- ===================================================== --}}

        <section class="document-section mb-12">

            <span class="document-marker"></span>


            <div class="section-label">
                Supporting evidence
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
                Upload screenshots
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
                Upload screenshots that help explain the problem.
                Vision AI can use them as supporting evidence during analysis.
            </p>



            {{-- Upload area --}}

            <label
                for="screenshots"
                class="
                    mt-6
                    flex
                    cursor-pointer
                    flex-col
                    items-center
                    justify-center
                    rounded-xl
                    border
                    border-dashed
                    border-gray-300
                    bg-gray-50
                    px-6
                    py-10
                    text-center
                    transition
                    hover:border-[#111d8f]
                    hover:bg-[#fafaff]
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-8 w-8 text-[#111d8f]"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 16V4m0 0L8 8m4-4l4 4M5 12v6a2 2 0 002 2h10a2 2 0 002-2v-6"
                    />

                </svg>


                <span
                    class="
                        mt-3
                        text-sm
                        font-semibold
                        text-gray-800
                    "
                >
                    Choose screenshots
                </span>


                <span
                    class="
                        mt-1
                        text-xs
                        text-gray-400
                    "
                >
                    PNG, JPG, WEBP or PDF · Maximum 10 MB per file
                </span>


                <input
                    id="screenshots"
                    type="file"
                    name="screenshots[]"
                    multiple
                    accept=".jpg,.jpeg,.png,.webp,.pdf"
                    class="sr-only"
                >

            </label>



            {{-- Selected files --}}

            <div
                id="selectedFiles"
                class="mt-4 space-y-2"
            ></div>

        </section>



        {{-- ===================================================== --}}
        {{-- ACCOUNT INFORMATION --}}
        {{-- ===================================================== --}}

        <section class="document-section mb-12">

            <span class="document-marker document-marker-olive"></span>


            <div class="section-label">
                Account information
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
                Account details
            </h2>


            <p class="mt-2 text-sm leading-6 text-gray-500">
                These details can help us prepare more relevant
                recovery guidance.
            </p>



            {{-- Username + Email --}}

            <div
                class="
                    mt-7
                    grid
                    grid-cols-1
                    gap-6
                    md:grid-cols-2
                "
            >

                {{-- Username --}}

                <div>

                    <label
                        for="username"
                        class="
                            text-xs
                            font-bold
                            uppercase
                            tracking-wide
                            text-gray-500
                        "
                    >
                        Username
                    </label>


                    <input
                        id="username"
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="@yourusername"
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
                            outline-none
                            placeholder:text-gray-300
                            focus:border-[#111d8f]
                            focus:ring-0
                        "
                    >

                </div>



                {{-- Email --}}

                <div>

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
                        Email linked to account
                    </label>


                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
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
                            outline-none
                            placeholder:text-gray-300
                            focus:border-[#111d8f]
                            focus:ring-0
                        "
                    >

                </div>

            </div>



            {{-- Account type --}}

            <div class="mt-8">

                <label
                    for="account_type"
                    class="
                        text-xs
                        font-bold
                        uppercase
                        tracking-wide
                        text-gray-500
                    "
                >
                    Account type
                </label>


                <select
                    id="account_type"
                    name="account_type"
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
                        outline-none
                        focus:border-[#111d8f]
                        focus:ring-0
                    "
                >

                    <option value="">
                        Select account type
                    </option>


                    <option
                        value="personal"
                        {{ old('account_type') === 'personal' ? 'selected' : '' }}
                    >
                        Personal account
                    </option>


                    <option
                        value="business"
                        {{ old('account_type') === 'business' ? 'selected' : '' }}
                    >
                        Business account
                    </option>


                    <option
                        value="creator"
                        {{ old('account_type') === 'creator' ? 'selected' : '' }}
                    >
                        Creator account
                    </option>

                </select>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- SUBMIT --}}
        {{-- ===================================================== --}}

        <div
            class="
                flex
                justify-end
                border-t
                border-gray-200
                pt-7
            "
        >

            <button
                type="submit"
                class="
                    inline-flex
                    items-center
                    gap-3
                    rounded-lg
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

                Continue to analysis


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

        </div>

    </form>


</x-case-shell>

@endsection



{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

@push('scripts')

<script>

    /*
    |--------------------------------------------------------------------------
    | Description character counter
    |--------------------------------------------------------------------------
    */

    const description =
        document.getElementById('description');

    const descriptionCounter =
        document.getElementById('descriptionCounter');


    if (description && descriptionCounter) {

        function updateCounter() {

            descriptionCounter.textContent =
                `${description.value.length}/500`;

        }


        description.addEventListener(
            'input',
            updateCounter
        );


        updateCounter();

    }



    /*
    |--------------------------------------------------------------------------
    | Screenshot selection
    |--------------------------------------------------------------------------
    */

    const screenshotInput =
        document.getElementById('screenshots');

    const selectedFiles =
        document.getElementById('selectedFiles');


    if (screenshotInput && selectedFiles) {

        screenshotInput.addEventListener(
            'change',
            function () {

                selectedFiles.innerHTML = '';


                const files =
                    Array.from(this.files);


                /*
                |--------------------------------------------------------------------------
                | No files
                |--------------------------------------------------------------------------
                */

                if (files.length === 0) {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Maximum 5 files
                |--------------------------------------------------------------------------
                */

                if (files.length > 5) {

                    const warning =
                        document.createElement('div');

                    warning.className =
                        'rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700';

                    warning.textContent =
                        'You can upload a maximum of 5 screenshots.';

                    selectedFiles.appendChild(
                        warning
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Display selected files
                |--------------------------------------------------------------------------
                */

                files.slice(0, 5).forEach(
                    function (file) {

                        const item =
                            document.createElement('div');


                        item.className =
                            'flex items-center justify-between gap-4 rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm';



                        const left =
                            document.createElement('div');


                        left.className =
                            'flex min-w-0 items-center gap-3';



                        /*
                        | File icon
                        */

                        const icon =
                            document.createElement('div');


                        icon.className =
                            'flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-[#f1f1f8] text-[#111d8f]';



                        icon.innerHTML =
                            `
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor"
                                    class="h-4 w-4"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 16V4m0 0L8 8m4-4l4 4M5 12v6a2 2 0 002 2h10a2 2 0 002-2v-6"
                                    />
                                </svg>
                            `;



                        /*
                        | File name
                        */

                        const name =
                            document.createElement('span');


                        name.className =
                            'truncate font-medium text-gray-800';


                        name.textContent =
                            file.name;



                        left.appendChild(icon);

                        left.appendChild(name);



                        /*
                        | File size
                        */

                        const size =
                            document.createElement('span');


                        size.className =
                            'shrink-0 text-xs text-gray-400';


                        size.textContent =
                            `${(file.size / 1024 / 1024).toFixed(2)} MB`;



                        item.appendChild(left);

                        item.appendChild(size);


                        selectedFiles.appendChild(item);

                    }
                );

            }
        );

    }

</script>

@endpush