@props(['location'])

<form
    method="POST"
    action="{{ $location->exists ? route('admin.locations.update', $location) : route('admin.locations.store') }}"
    class="space-y-6"
>
    @csrf
    @if ($location->exists)
        @method('PUT')
    @endif

    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Fields marked <span class="text-error-600">*</span> are required.</p>

    @if ($location->exists)
        <div>
            <p class="mb-1.5 text-theme-sm font-medium text-gray-700 dark:text-gray-400">Code</p>
            <p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ $location->code }} <span class="text-theme-xs">(auto-generated, cannot be changed)</span></p>
        </div>
    @endif

    <div>
        <label for="label" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Label <span class="text-error-600">*</span></label>
        <input type="text" id="label" name="label" value="{{ old('label', $location->label) }}" required
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        @error('label') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="applies_to" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Applies To <span class="text-error-600">*</span></label>
        <select id="applies_to" name="applies_to" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @foreach (\App\Enums\LocationScope::cases() as $scope)
                <option value="{{ $scope->value }}" @selected(old('applies_to', $location->applies_to?->value) === $scope->value)>
                    {{ str($scope->value)->headline() }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-theme-xs text-gray-500 dark:text-gray-400">Whether this location can be picked for pickup, return, or both.</p>
        @error('applies_to') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="flex items-center gap-2 text-theme-sm text-gray-700 dark:text-gray-400">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $location->is_active ?? true)) class="rounded border-gray-300">
            Active
        </label>
        <p class="mt-1 text-theme-xs text-gray-500 dark:text-gray-400">Inactive locations no longer appear on the booking form but stay attached to any existing bookings.</p>
    </div>

    <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
        {{ $location->exists ? 'Update Location' : 'Create Location' }}
    </button>
</form>
