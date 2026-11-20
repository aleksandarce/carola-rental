@component('layouts.admin', ['title' => 'Cars'])

    @if (session('status'))
        <p class="mb-6 rounded-lg bg-success-50 px-4 py-3 text-theme-sm text-success-600 dark:bg-success-500/15 dark:text-success-500">
            {{ session('status') }}
        </p>
    @endif

    @if (session('error'))
        <p class="mb-6 rounded-lg bg-error-50 px-4 py-3 text-theme-sm text-error-600 dark:bg-error-500/15 dark:text-error-500">
            {{ session('error') }}
        </p>
    @endif

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.cars.index') }}" class="flex flex-wrap items-center gap-3">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or brand..."
                class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
            >

            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                <option value="">Any Status</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>

            <select name="type" class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                <option value="">Any Type</option>
                @foreach (\App\Enums\CarType::cases() as $type)
                    <option value="{{ $type->value }}" @selected(request('type') === $type->value)>
                        {{ str($type->value)->headline() }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                Filter
            </button>

            @if (request('search') || request('status') || request('type'))
                <a href="{{ route('admin.cars.index') }}" class="text-theme-sm text-gray-500 hover:underline dark:text-gray-400">
                    Clear
                </a>
            @endif
        </form>

        <a
            href="{{ route('admin.cars.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600"
        >
            Add New Car
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="w-full overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-y border-gray-100 dark:border-gray-800">
                        <x-admin.sortable-header route="admin.cars.index" column="name" label="Name" :current-sort="$sort" :current-direction="$direction" />
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Brand</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Type</p></th>
                        <x-admin.sortable-header route="admin.cars.index" column="daily_rate" label="Daily Rate" :current-sort="$sort" :current-direction="$direction" />
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</p></th>
                        <th class="py-3 text-right"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Actions</p></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($cars as $car)
                        <tr>
                            <td class="py-3"><p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $car->name }}</p></td>
                            <td class="py-3"><p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ $car->brand }}</p></td>
                            <td class="py-3"><p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ str($car->type->value)->headline() }}</p></td>
                            <td class="py-3"><p class="text-theme-sm text-gray-500 dark:text-gray-400">${{ number_format($car->daily_rate, 2) }}</p></td>
                            <td class="py-3">
                                <p @class([
                                    'w-fit rounded-full px-2 py-0.5 text-theme-xs font-medium',
                                    'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' => $car->is_active,
                                    'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' => ! $car->is_active,
                                ])>
                                    {{ $car->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.cars.edit', $car) }}" class="text-theme-sm font-medium text-brand-500 hover:underline">
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.cars.destroy', $car) }}"
                                        onsubmit="return confirm('Delete {{ $car->name }}? This cannot be undone.');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-theme-sm font-medium text-error-600 hover:underline">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                No cars match your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $cars->links() }}
        </div>
    </div>

@endcomponent
