@component('layouts.admin', ['title' => 'Insurance Settings'])

    @if (session('status'))
        <p class="mb-6 rounded-lg bg-success-50 px-4 py-3 text-theme-sm text-success-600 dark:bg-success-500/15 dark:text-success-500">
            {{ session('status') }}
        </p>
    @endif

    <p class="mb-6 text-theme-sm text-gray-500 dark:text-gray-400">
        These are the 3 insurance packages customers choose from when booking. You can retune each package's name, daily rate, and
        coverage text — the 3 packages themselves can't be added to or removed.
    </p>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @foreach ($insuranceSettings as $setting)
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="mb-4 text-theme-xs font-medium text-gray-500 dark:text-gray-400">Code: {{ $setting->code }}</p>

                <form method="POST" action="{{ route('admin.insurance-settings.update', $setting) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="label-{{ $setting->id }}" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Label</label>
                        <input type="text" id="label-{{ $setting->id }}" name="label" value="{{ old('label', $setting->label) }}" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        @error('label') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="daily_rate-{{ $setting->id }}" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Daily Rate ($)</label>
                        <input type="number" step="0.01" min="0" id="daily_rate-{{ $setting->id }}" name="daily_rate" value="{{ old('daily_rate', $setting->daily_rate) }}" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        @error('daily_rate') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="coverage-{{ $setting->id }}" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Coverage</label>
                        <textarea id="coverage-{{ $setting->id }}" name="coverage" rows="3" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">{{ old('coverage', $setting->coverage) }}</textarea>
                        @error('coverage') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                        Save
                    </button>
                </form>
            </div>
        @endforeach
    </div>

@endcomponent
