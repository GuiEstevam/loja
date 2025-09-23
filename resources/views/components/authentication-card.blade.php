<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
    <!-- Logo -->
    <div class="mb-8">
        {{ $logo }}
    </div>

    <!-- Card de Login -->
    <div class="w-full sm:max-w-md mt-6 px-8 py-6 bg-white shadow-lg overflow-hidden sm:rounded-xl border border-gray-200">
        {{ $slot }}
    </div>
    
    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-600">
        <p>&copy; {{ date('Y') }} SkyFashion. Todos os direitos reservados.</p>
    </div>
</div>
