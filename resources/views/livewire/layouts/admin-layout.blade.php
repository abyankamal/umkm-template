<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} - UMKM Template</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <livewire:layouts.admin-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            <livewire:layouts.admin-header />

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <livewire:layouts.admin-footer />
        </div>
    </div>

    @livewireScripts
</body>

</html>