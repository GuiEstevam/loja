<!DOCTYPE html>
<html lang="pt-BR">
@php use Illuminate\Support\Str; @endphp

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Loja Dropshipping')</title>
  @vite('resources/css/app.css')
  <script src="//unpkg.com/alpinejs" defer></script>
  @stack('styles')
  <script type="module" src="https://unpkg.com/ionicons@7.2.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.2.2/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">
  <x-navbar />
  <main class="flex-1">
    @yield('content')
  </main>
  <x-footer />

  {{-- Modal de feedback global --}}
  @if (session('success') || $errors->any())
    <div id="feedbackModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-lg relative">
        <button type="button" id="closeModal"
          class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl font-bold leading-none focus:outline-none"
          aria-label="Fechar">
          &times;
        </button>
        <h2 class="text-xl font-bold mb-4">Mensagem</h2>
        @if (session('success'))
          <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
          <div class="mb-4 text-red-600">
            <ul class="list-disc list-inside">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('feedbackModal');
        if (modal) {
          // Fecha ao clicar no botão (X)
          modal.querySelector('#closeModal').onclick = function() {
            modal.style.display = 'none';
          };
          // Fecha ao clicar fora do conteúdo
          modal.onclick = function(e) {
            if (e.target === modal) {
              modal.style.display = 'none';
            }
          };
          // Fecha ao pressionar ESC
          document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
              modal.style.display = 'none';
            }
          });
        }
      });
    </script>
  @endif

  @stack('scripts')
</body>

</html>
