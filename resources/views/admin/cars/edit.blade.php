@component('layouts.admin', ['title' => 'Edit Car'])
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <x-car-form :car="$car" />
    </div>
@endcomponent