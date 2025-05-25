<!DOCTYPE html>
<html lang="pt-BR">
@php use Illuminate\Support\Str; @endphp

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Loja Dropshipping')</title>
  @vite('resources/css/app.css')
  <script src="//unpkg.com/alpinejs" defer></script>
  @stack('styles') {{-- Aqui renderiza os styles das views --}}
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">
  <x-navbar />
  <main class="flex-1">
    @yield('content')
  </main>
  <x-footer />
  @stack('scripts') {{-- Aqui renderiza os scripts das views --}}
</body>

</html>
