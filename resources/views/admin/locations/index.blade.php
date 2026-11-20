@component('layouts.admin', ['title' => 'Locations'])

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

    <div class="mb-6 flex items-center justify-end">
        <a
            href="{{ route('admin.locations.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600"
        >
            Add New Location
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="w-full overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-y border-gray-100 dark:border-gray-800">
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Label</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Code</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Applies To</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</p></th>
                        <th class="py-3 text-right"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Actions</p></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($locations as $location)
                        <tr>
                            <td class="py-3"><p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $location->label }}</p></td>
                            <td class="py-3"><p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ $location->code }}</p></td>
                            <td class="py-3"><p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ str($location->applies_to->value)->headline() }}</p></td>
                            <td class="py-3">
                                <p @class([
                                    'w-fit rounded-full px-2 py-0.5 text-theme-xs font-medium',
                                    'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' => $location->is_active,
                                    'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' => ! $location->is_active,
                                ])>
                                    {{ $location->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.locations.edit', $location) }}" class="text-theme-sm font-medium text-brand-500 hover:underline">
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.locations.destroy', $location) }}"
                                        onsubmit="return confirm('Delete {{ $location->label }}? This cannot be undone.');"
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
                            <td colspan="5" class="py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                No locations yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endcomponent
