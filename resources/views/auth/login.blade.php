<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Coffeeshop</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <div class="login-card shadow">
        <div class="login-logo">
            <div class="circle">C</div>
            <h4 class="fw-bold mt-2">CleopatraCoffee</h4>
            <p class="text-muted small">Silakan masuk ke akun Anda</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger small border-0">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login.auth') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="admin@gmail.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">MASUK SEKARANG</button>
        </form>
        
        <div class="text-center mt-4">
            <small class="text-muted">Lupa password? Hubungi IT Support.</small>
        </div>
    </div>

</body>
</html>