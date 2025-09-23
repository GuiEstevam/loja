<footer class="footer" aria-label="Rodapé do site">
  <div class="footer-container">
    <!-- Grid Principal -->
    <div class="footer-grid">
      <!-- Branding -->
      <div class="footer-brand">
        <a href="/" class="footer-logo">
          <img src="{{ asset('images/logo_light.png') }}" alt="SkyFashion" class="footer-logo-light">
          <img src="{{ asset('images/logo_dark.png') }}" alt="SkyFashion" class="footer-logo-dark">
        </a>
        <p class="footer-description">
          Sua loja de moda online com as melhores tendências e preços competitivos.
        </p>
        <div class="footer-social">
          <a href="#" class="social-link" aria-label="Instagram">
            <ion-icon name="logo-instagram"></ion-icon>
          </a>
          <a href="#" class="social-link" aria-label="Facebook">
            <ion-icon name="logo-facebook"></ion-icon>
          </a>
          <a href="#" class="social-link" aria-label="Twitter">
            <ion-icon name="logo-twitter"></ion-icon>
          </a>
          <a href="#" class="social-link" aria-label="YouTube">
            <ion-icon name="logo-youtube"></ion-icon>
          </a>
        </div>
      </div>

      <!-- Links Rápidos -->
      <div class="footer-section">
        <h3 class="footer-title">Navegação</h3>
        <nav class="footer-nav">
          <a href="/" class="footer-link">Início</a>
          <a href="/produtos" class="footer-link">Produtos</a>
          <a href="/categorias" class="footer-link">Categorias</a>
          <a href="/marcas" class="footer-link">Marcas</a>
          <a href="/ofertas" class="footer-link">Ofertas</a>
        </nav>
      </div>

      <!-- Suporte -->
      <div class="footer-section">
        <h3 class="footer-title">Suporte</h3>
        <nav class="footer-nav">
          <a href="/ajuda" class="footer-link">Central de Ajuda</a>
          <a href="/contato" class="footer-link">Fale Conosco</a>
          <a href="/trocas" class="footer-link">Trocas e Devoluções</a>
          <a href="/frete" class="footer-link">Informações de Frete</a>
          <a href="/garantia" class="footer-link">Garantia</a>
        </nav>
      </div>

      <!-- Newsletter -->
      <div class="footer-section">
        <h3 class="footer-title">Newsletter</h3>
        <p class="footer-newsletter-text">
          Receba as melhores ofertas e novidades em primeira mão!
        </p>
        <form class="footer-newsletter-form">
          <div class="newsletter-input-group">
            <input type="email" placeholder="Seu melhor e-mail" class="newsletter-input" required>
            <button type="submit" class="newsletter-btn">
              <ion-icon name="paper-plane-outline"></ion-icon>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Informações de Contato -->
    <div class="footer-contact">
      <div class="contact-item">
        <ion-icon name="location-outline" class="contact-icon"></ion-icon>
        <div class="contact-info">
          <span class="contact-label">Endereço</span>
          <span class="contact-value">Lisboa, Portugal</span>
        </div>
      </div>
      <div class="contact-item">
        <ion-icon name="call-outline" class="contact-icon"></ion-icon>
        <div class="contact-info">
          <span class="contact-label">Telefone</span>
          <a href="tel:+351999999999" class="contact-value">+351 999 999 999</a>
        </div>
      </div>
      <div class="contact-item">
        <ion-icon name="mail-outline" class="contact-icon"></ion-icon>
        <div class="contact-info">
          <span class="contact-label">E-mail</span>
          <a href="mailto:contato@skyfashion.pt" class="contact-value">contato@skyfashion.pt</a>
        </div>
      </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
      <div class="footer-bottom-content">
        <p class="footer-copyright">
          &copy; {{ date('Y') }} SkyFashion. Todos os direitos reservados.
        </p>
        <div class="footer-legal">
          <a href="/privacidade" class="legal-link">Política de Privacidade</a>
          <a href="/termos" class="legal-link">Termos de Uso</a>
          <a href="/cookies" class="legal-link">Política de Cookies</a>
        </div>
      </div>
    </div>
  </div>
</footer>
