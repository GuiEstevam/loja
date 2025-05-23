<!DOCTYPE html>
<html lang="pt-BR">
@php use Illuminate\Support\Str; @endphp

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Loja Dropshipping')</title>
  @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">
  <x-navbar />
  <main class="flex-1">
    @yield('content')
  </main>
  <x-footer />
</body>

</html>
