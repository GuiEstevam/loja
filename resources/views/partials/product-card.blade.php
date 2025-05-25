<a href="{{ route('shop.products.show', $product) }}"
  class="group bg-white border border-slate-200 rounded-3xl shadow hover:shadow-2xl transition flex flex-col overflow-hidden h-[420px] w-full cursor-pointer">
  <!-- Imagem do produto -->
  <div class="w-full flex items-center justify-center bg-[#f6f6f6] rounded-t-3xl" style="height: 220px;">
    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
      class="object-contain h-[180px] w-[180px] group-hover:scale-105 transition-transform duration-300" loading="lazy">
  </div>
  <!-- Conteúdo -->
  <div class="flex-1 flex flex-col justify-end w-full px-6 py-6 bg-white">
    <h3 class="font-semibold text-lg mb-2 text-gray-900 truncate text-left" title="{{ $product->name }}">
      {{ $product->name }}
    </h3>
    <div class="mb-1 text-left">
      @php $fakePrice = $product->price * 1.2; @endphp
      <span class="text-gray-400 text-base line-through mr-2">De: R$ {{ number_format($fakePrice, 2, ',', '.') }}</span>
      <span class="text-blue-600 font-bold text-lg block">Por: R$
        {{ number_format($product->price, 2, ',', '.') }}</span>
    </div>
    <button
      class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-full font-bold text-base shadow hover:bg-blue-700 transition">
      Comprar
    </button>
  </div>
</a>
