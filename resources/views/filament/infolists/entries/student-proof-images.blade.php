@php
    /** @var array $getState */
    $proofs = $getState() ?: [];
@endphp

@if (!empty($proofs))
    <div class="space-y-4">
        @foreach ($proofs as $proof)
            @php
                $front = $proof['front'] ?? null;
                $back = $proof['back'] ?? null;
                $name = $proof['passenger_name'] ?? 'Student proof';
                $studentNumber = $proof['student_number'] ?? null;
            @endphp

            <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                <p class="font-semibold text-sm">{{ $name }}</p>
                @if ($studentNumber)
                    <p class="text-xs text-gray-500">Student number: {{ $studentNumber }}</p>
                @endif

                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    @php $frontUrl = storage_asset_path($front); @endphp
                    @if ($frontUrl)
                        <div>
                            <p class="mb-1 text-xs uppercase tracking-wide text-gray-500">Front</p>
                            <a href="{{ $frontUrl }}" target="_blank">
                                <img src="{{ $frontUrl }}" class="max-h-60 rounded-md border border-gray-300 object-contain" alt="Student proof front" />
                            </a>
                        </div>
                    @endif

                    @php $backUrl = storage_asset_path($back); @endphp
                    @if ($backUrl)
                        <div>
                            <p class="mb-1 text-xs uppercase tracking-wide text-gray-500">Back</p>
                            <a href="{{ $backUrl }}" target="_blank">
                                <img src="{{ $backUrl }}" class="max-h-60 rounded-md border border-gray-300 object-contain" alt="Student proof back" />
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
