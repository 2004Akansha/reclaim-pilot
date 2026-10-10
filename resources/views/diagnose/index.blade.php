@extends('layouts.reclaimpilot')

@section('title', 'ReclaimPilot - Diagnose')

@section('content')

    <x-stepper :current-step="1" />


    {{-- Page heading --}}

    <div class="mb-8">

        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-gray-900">
            Let's understand your case
        </h1>

        <p class="mt-3 text-gray-500 max-w-2xl leading-7">
            Share a few details about what happened.
            The more information you provide, the better we can help
            you prepare your recovery request.
        </p>

    </div>


    {{-- Validation errors --}}

    @if ($errors->any())

        <div
            class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5"
        >

            <p class="font-semibold text-red-800 mb-2">
                Please fix the following:
            </p>

            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('case.store') }}"
        enctype="multipart/form-data"
        class="space-y-6"
    >

        @csrf


        {{-- Issue --}}

        <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

            <h2 class="text-lg font-semibold">
                What seems to be the issue?
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Select the option that best describes your problem.
            </p>


            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">

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
                                p-5
                                transition
                                hover:border-emerald-300
                                hover:bg-emerald-50/30
                                peer-checked:border-emerald-500
                                peer-checked:bg-emerald-50
                                peer-checked:ring-2
                                peer-checked:ring-emerald-100
                            "
                        >

                            <div class="flex items-center gap-4">

                                <div
                                    class="w-11 h-11 rounded-xl bg-gray-50 flex items-center justify-center text-xl"
                                >
                                    {{ $issue['icon'] }}
                                </div>

                                <div>

                                    <div class="font-semibold text-gray-900">
                                        {{ $issue['title'] }}
                                    </div>

                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ $issue['description'] }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </label>

                @endforeach

            </div>

        </section>


        {{-- Description --}}

        <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <h2 class="text-lg font-semibold">

                        Tell us more

                        <span class="text-sm font-normal text-gray-400">
                            (optional)
                        </span>

                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Describe what happened in your own words.
                    </p>

                </div>

                <span
                    id="descriptionCounter"
                    class="text-xs text-gray-400 whitespace-nowrap"
                >
                    0/500
                </span>

            </div>


            <textarea
                id="description"
                name="description"
                rows="5"
                maxlength="500"
                placeholder="Example: My account was disabled after a notification appeared, but I believe this was a mistake..."
                class="mt-4 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 resize-none"
            >{{ old('description') }}</textarea>

        </section>


        {{-- Screenshots --}}

        <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

            <h2 class="text-lg font-semibold">

                Upload screenshots

                <span class="text-sm font-normal text-gray-400">
                    (optional)
                </span>

            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Upload screenshots that help explain your case.
            </p>


            <input
                id="screenshots"
                type="file"
                name="screenshots[]"
                multiple
                accept=".jpg,.jpeg,.png,.webp,.pdf"
                class="mt-5 block w-full text-sm text-gray-500
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-lg file:border-0
                       file:text-sm file:font-medium
                       file:bg-emerald-50
                       file:text-emerald-700
                       hover:file:bg-emerald-100"
            >

            <p class="mt-2 text-xs text-gray-400">
                PNG, JPG or PDF. Maximum 10 MB per file.
            </p>


            <div
                id="selectedFiles"
                class="mt-4 space-y-2"
            ></div>

        </section>


        {{-- Account details --}}

        <section class="bg-white border border-gray-200 rounded-2xl p-6 card-shadow">

            <h2 class="text-lg font-semibold">

                Account details

                <span class="text-sm font-normal text-gray-400">
                    (optional)
                </span>

            </h2>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                <div>

                    <label
                        for="username"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Username
                    </label>

                    <input
                        id="username"
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="@yourusername"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                    >

                </div>


                <div>

                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Email linked to account
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                    >

                </div>

            </div>


            <div class="mt-5">

                <label
                    for="account_type"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Account type
                </label>

                <select
                    id="account_type"
                    name="account_type"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
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


        {{-- Continue --}}

        <div class="pt-2">

            <button
                type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-4 rounded-xl transition flex items-center justify-center gap-2"
            >

                Continue to analysis

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 12h14m-6-6l6 6-6 6"
                    />

                </svg>

            </button>

        </div>

    </form>

@endsection


@push('scripts')

<script>

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


    const screenshotInput =
        document.getElementById('screenshots');

    const selectedFiles =
        document.getElementById('selectedFiles');

    if (screenshotInput && selectedFiles) {

        screenshotInput.addEventListener(
            'change',
            function () {

                selectedFiles.innerHTML = '';

                Array.from(this.files).forEach(
                    function (file) {

                        const item =
                            document.createElement('div');

                        item.className =
                            'flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 text-sm';

                        const name =
                            document.createElement('span');

                        name.className =
                            'truncate';

                        name.textContent =
                            file.name;

                        const size =
                            document.createElement('span');

                        size.className =
                            'text-xs text-gray-400 ml-4 whitespace-nowrap';

                        size.textContent =
                            `${(file.size / 1024 / 1024).toFixed(2)} MB`;

                        item.appendChild(name);
                        item.appendChild(size);

                        selectedFiles.appendChild(item);

                    }
                );

            }
        );

    }

</script>

@endpush