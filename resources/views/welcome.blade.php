@extends('layouts.app')

@section('title', 'Bem-vindo')

@push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <style>
    .custom-swiper-arrow {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 3rem;
      height: 3rem;
      border-radius: 9999px;
      background: #fff;
      box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.10);
      color: #1e293b;
      font-size: 1.8rem;
      z-index: 30;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
    }

    .custom-swiper-arrow:hover {
      background: #2563eb;
      color: #fff;
    }

    .custom-swiper-arrow svg {
      width: 2rem;
      height: 2rem;
      display: block;
    }
  </style>
@endpush

@section('content')
  <!-- Banner Hero Carrossel -->
  <section class="w-full mb-8">
    <div class="swiper bannerSwiper h-[400px] md:h-[500px] lg:h-[650px] overflow-hidden relative">
      <div class="swiper-wrapper">
        <!-- Banner 1 -->
        <div class="swiper-slide relative flex items-center justify-center h-full">
          <div class="absolute inset-0 w-full h-full"
            style="
            background-image: url('{{ asset('images/banner_1.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
          ">
          </div>
          <div class="absolute inset-0 w-full h-full"
            style="background: linear-gradient(to bottom, rgba(0,0,0,0.20) 0%, rgba(0,0,0,0.10) 60%, rgba(0,0,0,0.20) 100%);">
          </div>
          <div class="relative z-10 flex flex-col items-center justify-center w-full h-full px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 drop-shadow-lg tracking-wider">
              FRETE GRÁTIS PARA TODO O BRASIL
            </h1>
            <p class="text-2xl md:text-3xl text-white mt-2 max-w-3xl mx-auto font-semibold drop-shadow">
              Aproveite condições especiais por tempo limitado!
            </p>
          </div>
          <a href="{{ route('shop.products.index') }}" class="absolute inset-0 w-full h-full block z-30"
            aria-label="Ver Produtos"></a>
        </div>
        <!-- Banner 2 -->
        <div class="swiper-slide relative flex items-center justify-center h-full">
          <div class="absolute inset-0 w-full h-full"
            style="
            background-image: url('{{ asset('images/banner_2.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
          ">
          </div>
          <div class="absolute inset-0 w-full h-full"
            style="background: linear-gradient(to bottom, rgba(0,0,0,0.20) 0%, rgba(0,0,0,0.10) 60%, rgba(0,0,0,0.20) 100%);">
          </div>
          <div class="relative z-10 flex flex-col items-center justify-center w-full h-full px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 drop-shadow-lg tracking-wider">
              NOVA COLEÇÃO CHEGOU
            </h1>
            <p class="text-2xl md:text-3xl text-white mt-2 max-w-3xl mx-auto font-semibold drop-shadow">
              Descubra as tendências do ano com exclusividade
            </p>
          </div>
          <a href="{{ route('shop.products.index') }}" class="absolute inset-0 w-full h-full block z-30"
            aria-label="Ver Novidades"></a>
        </div>
        <!-- Banner 3 -->
        <div class="swiper-slide relative flex items-center justify-center h-full">
          <div class="absolute inset-0 w-full h-full"
            style="
            background-image: url('{{ asset('images/banner_3.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
          ">
          </div>
          <div class="absolute inset-0 w-full h-full"
            style="background: linear-gradient(to bottom, rgba(0,0,0,0.20) 0%, rgba(0,0,0,0.10) 60%, rgba(0,0,0,0.20) 100%);">
          </div>
          <div class="relative z-10 flex flex-col items-center justify-center w-full h-full px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 drop-shadow-lg tracking-wider">
              MAIS DO QUE APENAS "UMA LOJA"
            </h1>
            <p class="text-2xl md:text-3xl text-white mt-2 max-w-3xl mx-auto font-semibold drop-shadow">
              Um estilo de vida com qualidade e equilíbrio
            </p>
          </div>
          <a href="{{ route('shop.products.index') }}" class="absolute inset-0 w-full h-full block z-30"
            aria-label="Ver Produtos"></a>
        </div>
      </div>
      <div class="banner-pagination mt-2"></div>
      <div class="banner-button-prev custom-swiper-arrow absolute left-4 top-1/2 -translate-y-1/2">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </div>
      <div class="banner-button-next custom-swiper-arrow absolute right-4 top-1/2 -translate-y-1/2">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>
    </div>
  </section>
  <!-- Carrossel de produtos em destaque -->
  <section class="w-full">
    <h2 class="text-4xl md:text-5xl font-extrabold tracking-widest uppercase text-center mb-14">DESTAQUES</h2>
    <div class="relative max-w-8xl mx-auto px-4"> <!-- padding lateral aqui -->
      <div class="product-button-prev custom-swiper-arrow absolute left-4 top-1/2 -translate-y-1/2 z-20">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </div>
      <div class="swiper productSwiper w-full">
        <div class="swiper-wrapper">
          @foreach ($products as $product)
            <div class="swiper-slide">
              <a href="{{ route('shop.products.show', $product) }}"
                class="bg-white border border-slate-200 rounded-3xl shadow-lg hover:shadow-2xl transition w-full h-[480px] flex flex-col overflow-hidden group">
                <!-- Imagem ocupa todo o topo do card -->
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                  class="object-cover w-full h-[340px] group-hover:scale-105 transition-transform duration-300 rounded-t-3xl">
                <div class="flex-1 flex flex-col justify-start w-full px-5 py-4 bg-white">
                  <h3 class="font-bold text-xl md:text-2xl mb-2 text-gray-900 truncate text-left"
                    title="{{ $product->name }}">
                    {{ $product->name }}
                  </h3>
                  <div class="mb-1 text-left">
                    @php $fakePrice = $product->price * 1.2; @endphp
                    <span class="text-gray-400 text-base line-through mr-2">De: R$
                      {{ number_format($fakePrice, 2, ',', '.') }}</span>
                    <span class="text-blue-600 font-semibold text-lg block">Por: R$
                      {{ number_format($product->price, 2, ',', '.') }}</span>
                  </div>
                </div>
              </a>
            </div>
          @endforeach
        </div>
        <div class="product-pagination text-center mt-8"></div>
      </div>
      <div class="product-button-next custom-swiper-arrow absolute right-4 top-1/2 -translate-y-1/2 z-20">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
    // Banner carrossel
    new Swiper('.bannerSwiper', {
      slidesPerView: 1,
      loop: true,
      speed: 900,
      pagination: {
        el: '.banner-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.banner-button-next',
        prevEl: '.banner-button-prev',
      },
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
    });

    // Carrossel de produtos: 4 itens, espaço lateral pequeno, setas estilizadas
    new Swiper('.productSwiper', {
      slidesPerView: 1,
      spaceBetween: 8,
      loop: true,
      pagination: {
        el: '.product-pagination',
        clickable: true
      },
      navigation: {
        nextEl: '.product-button-next',
        prevEl: '.product-button-prev'
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 16
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 24
        },
        1280: {
          slidesPerView: 4,
          spaceBetween: 32
        } // espaço entre os cards em desktop
      }
    });
  </script>
@endpush
