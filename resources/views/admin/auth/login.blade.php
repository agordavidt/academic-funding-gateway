<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Login - Academic Funding Gateway</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="{{asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('assets/css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css')}}" />
    
    <style>
        :root {
            --color-light-background: #f9f7f0;
            --color-primary: #18b7be;
            --color-secondary: #178ca4;
            --color-dark: #072a40;
        }

        body {
            background-color: var(--color-light-background);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Public Sans', sans-serif;
            color: var(--color-dark);
        }
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            margin: 2rem;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-section h3 {
            color: var(--color-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .logo-section p {
            color: #6e707e;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            border: 1px solid #d1d3e2;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(24, 183, 190, 0.25);
        }
        
        .btn-login {
            background: var(--color-primary);
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: var(--color-secondary);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(23, 140, 164, 0.3);
        }
        
        .form-check-input:checked {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        
        .alert {
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-link a {
            color: var(--color-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .back-link a:hover {
            text-decoration: underline;
            color: var(--color-primary);
        }
        
        .input-group-text {
            background: white;
            border-radius: 8px 0 0 8px;
            border-right: none;
            color: var(--color-secondary);
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: #858796;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <h3 class="fw-bold">Academic Funding Gateway</h3>
            <p>Admin Portal</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        placeholder="name@example.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="Password"
                        required
                        autocomplete="current-password"
                    >
                    <span class="input-group-text toggle-password" style="cursor: pointer;" title="Show password">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login to Admin Panel
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('landing') }}">
                <i class="fas fa-arrow-left me-1"></i>Back to Main Site
            </a>
        </div>
        
        <div class="login-footer">
            &copy; {{ date('Y') }} Academic Funding Gateway
        </div>
    </div>

    <script src="{{asset('assets/js/core/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
        
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                this.title = type === 'password' ? 'Show password' : 'Hide password';
            });
        });
    </script>
</body>
</html>