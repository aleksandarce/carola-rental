@props(['car'])

<form
    method="POST"
    action="{{ $car->exists ? route('admin.cars.update', $car) : route('admin.cars.store') }}"
    enctype="multipart/form-data"
    class="space-y-6"
>
    @csrf
    @if ($car->exists)
        @method('PUT')
    @endif

    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Fields marked <span class="text-error-600">*</span> are required.</p>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <label for="name" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Name <span class="text-error-600">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $car->name) }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('name') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="brand" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Brand <span class="text-error-600">*</span></label>
            <input type="text" id="brand" name="brand" value="{{ old('brand', $car->brand) }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('brand') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="type" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Type <span class="text-error-600">*</span></label>
            <select id="type" name="type" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @foreach (\App\Enums\CarType::cases() as $type)
                    <option value="{{ $type->value }}" @selected(old('type', $car->type?->value) === $type->value)>
                        {{ str($type->value)->headline() }}
                    </option>
                @endforeach
            </select>
            @error('type') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="daily_rate" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Daily Rate ($) <span class="text-error-600">*</span></label>
            <input type="number" step="0.01" min="0" id="daily_rate" name="daily_rate" value="{{ old('daily_rate', $car->daily_rate) }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('daily_rate') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="seat_capacity" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Seat Capacity <span class="text-error-600">*</span></label>
            <input type="number" min="1" max="20" id="seat_capacity" name="seat_capacity" value="{{ old('seat_capacity', $car->seat_capacity) }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('seat_capacity') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="doors" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Doors <span class="text-error-600">*</span></label>
            <input type="number" min="1" max="10" id="doors" name="doors" value="{{ old('doors', $car->doors) }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('doors') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="large_luggage" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Large Luggage</label>
            <input type="number" min="0" max="20" id="large_luggage" name="large_luggage" value="{{ old('large_luggage', $car->large_luggage) }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('large_luggage') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="small_luggage" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Small Luggage</label>
            <input type="number" min="0" max="20" id="small_luggage" name="small_luggage" value="{{ old('small_luggage', $car->small_luggage) }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('small_luggage') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="fuel_type" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Fuel Type <span class="text-error-600">*</span></label>
            <select id="fuel_type" name="fuel_type" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @foreach (\App\Enums\FuelType::cases() as $fuelType)
                    <option value="{{ $fuelType->value }}" @selected(old('fuel_type', $car->fuel_type?->value) === $fuelType->value)>
                        {{ $fuelType->value }}
                    </option>
                @endforeach
            </select>
            @error('fuel_type') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="transmission" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Transmission <span class="text-error-600">*</span></label>
            <select id="transmission" name="transmission" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @foreach (\App\Enums\Transmission::cases() as $transmission)
                    <option value="{{ $transmission->value }}" @selected(old('transmission', $car->transmission?->value) === $transmission->value)>
                        {{ $transmission->value }}
                    </option>
                @endforeach
            </select>
            @error('transmission') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="mileage" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Mileage</label>
            <input type="text" id="mileage" name="mileage" value="{{ old('mileage', $car->mileage) }}" placeholder="e.g. 12 kmpl"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('mileage') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="engine_power" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Engine Power</label>
            <input type="text" id="engine_power" name="engine_power" value="{{ old('engine_power', $car->engine_power) }}" placeholder="e.g. 260 HP"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('engine_power') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="location" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Location <span class="text-error-600">*</span></label>
            <input type="text" id="location" name="location" value="{{ old('location', $car->location) }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            @error('location') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="short_description" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Short Description <span class="text-error-600">*</span></label>
        <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $car->short_description) }}" required
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        @error('short_description') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Description <span class="text-error-600">*</span></label>
        <textarea id="description" name="description" rows="4" required
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">{{ old('description', $car->description) }}</textarea>
        @error('description') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="image" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-400">Image</label>

        @if ($car->exists && $car->image_path)
            <img src="{{ $car->thumbnail }}" alt="{{ $car->name }}" class="mb-3 h-32 w-auto rounded-lg object-cover">
        @endif

        <input type="file" id="image" name="image" accept="image/*"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        @error('image') <p class="mt-1 text-theme-xs text-error-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-6">
        <label class="flex items-center gap-2 text-theme-sm text-gray-700 dark:text-gray-400">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $car->is_featured)) class="rounded border-gray-300">
            Featured
        </label>

        <label class="flex items-center gap-2 text-theme-sm text-gray-700 dark:text-gray-400">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $car->is_active ?? true)) class="rounded border-gray-300">
            Active
        </label>
    </div>

    <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
        {{ $car->exists ? 'Update Car' : 'Create Car' }}
    </button>
</form>
