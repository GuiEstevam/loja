<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Loja Dropshipping')</title>
  @vite('resources/css/app.css')
</head>


<body class="bg-gray-50 text-gray-900">
  <x-navbar />
  <main>
    @yield('content')
  </main>
  <x-footer />:

</body>

</html>
