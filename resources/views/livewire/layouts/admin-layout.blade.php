<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <livewire:layouts.admin-sidebar />

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <livewire:layouts.admin-header />

            <!-- Page Content -->
            <main class="p-6 flex-grow">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <livewire:layouts.admin-footer />
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>