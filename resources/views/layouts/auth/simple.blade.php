<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark carola-brand">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="mb-2 flex flex-col items-center" wire:navigate>
                    <img src="{{ asset('carola/assets/images/logo.png') }}" alt="{{ config('app.name', 'Carola') }}" class="h-8 w-auto dark:hidden">
                    <img src="{{ asset('carola/assets/images/footer-logo.png') }}" alt="{{ config('app.name', 'Carola') }}" class="hidden h-8 w-auto dark:block">
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
