<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SkyFashion</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        
        .login-header {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .logo {
            position: relative;
            z-index: 1;
        }
        
        .logo-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
        }
        
        .logo-icon img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .logo-text {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .logo-subtitle {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .login-form {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-input::placeholder {
            color: #9ca3af;
        }
        
        .form-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .form-checkbox input {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: #3b82f6;
        }
        
        .form-checkbox label {
            font-size: 14px;
            color: #6b7280;
            cursor: pointer;
        }
        
        .form-links {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .form-link {
            color: #3b82f6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .form-link:hover {
            color: #2563eb;
        }
        
        .login-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .login-button:hover::before {
            left: 100%;
        }
        
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .login-button svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }
        
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .footer {
            text-align: center;
            padding: 20px 30px;
            background: #f9fafb;
            color: #6b7280;
            font-size: 12px;
        }
        
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .login-container {
                border-radius: 15px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .login-form {
                padding: 30px 20px;
            }
            
            .form-links {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header com Logo -->
        <div class="login-header">
            <div class="logo">
                <div class="logo-icon">
                    <img src="{{ asset('images/logo_light.png') }}" alt="SkyFashion Logo">
                </div>
                <div class="logo-text">SkyFashion</div>
                <div class="logo-subtitle">Sua loja de moda online</div>
            </div>
        </div>
        
        <!-- Formulário de Login -->
        <div class="login-form">
            @if ($errors->any())
                <div class="error-message">
                    <strong>Erro:</strong>
                    <ul style="margin-top: 5px; margin-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @session('status')
                <div class="success-message">
                    {{ $value }}
                </div>
            @endsession
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="form-input" 
                        placeholder="seu@email.com"
                        required 
                        autofocus 
                        autocomplete="username"
                    />
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Senha</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="••••••••"
                        required 
                        autocomplete="current-password"
                    />
                </div>
                
                <div class="form-checkbox">
                    <input id="remember_me" type="checkbox" name="remember" />
                    <label for="remember_me">Lembrar de mim</label>
                </div>
                
                <div class="form-links">
                    <a href="{{ route('register') }}" class="form-link">Criar conta</a>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="form-link">Esqueceu sua senha?</a>
                    @endif
                </div>
                
                <button type="submit" class="login-button">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4m-5-4l5-5-5-5m5 5H3"/>
                    </svg>
                    Entrar
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} SkyFashion. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>