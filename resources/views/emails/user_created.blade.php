<!DOCTYPE html>
<html>
<head>
    <title>Akun Baru</title>
</head>
<body>
    <h2>Halo, {{ $user->name }}</h2>
    
    <p>Selamat! Akun Anda sudah ditambahkan ke dalam sistem.</p>
    
    <p>Berikut adalah detail akun Anda untuk login:</p>
    
    <ul>
        {{-- Hapus $this->, ganti jadi langsung $user dan $password --}}
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>Silakan login melalui tautan berikut:</p>
    <p>
        <a href="{{ url('/login') }}">Klik disini untuk Login</a>
    </p>

    <p>Terima kasih.</p>
</body>
</html>