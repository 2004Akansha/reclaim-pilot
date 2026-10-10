@php

    $steps = [
        1 => 'Diagnose',
        2 => 'Analyze',
        3 => 'Prepare',
        4 => 'Escalate',
    ];

@endphp

<div class="mb-10">

    <div class="flex items-center">

        @foreach($steps as $number => $label)

            <div class="flex flex-col items-center shrink-0">

                @if($number < $currentStep)

                    <div
                        class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-semibold"
                    >
                        ✓
                    </div>

                @elseif($number === $currentStep)

                    <div
                        class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-semibold ring-4 ring-emerald-100"
                    >
                        {{ $number }}
                    </div>

                @else

                    <div
                        class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-sm font-semibold"
                    >
                        {{ $number }}
                    </div>

                @endif


                <span
                    class="
                        mt-2
                        text-xs
                        whitespace-nowrap
                        {{ $number <= $currentStep
                            ? 'font-semibold text-emerald-700'
                            : 'text-gray-500' }}
                    "
                >
                    {{ $label }}
                </span>

            </div>


            @if($number < count($steps))

                <div
                    class="
                        flex-1
                        h-px
                        mx-3
                        mb-6
                        {{ $number < $currentStep
                            ? 'bg-emerald-500'
                            : 'bg-gray-200' }}
                    "
                ></div>

            @endif

        @endforeach

    </div>

</div>