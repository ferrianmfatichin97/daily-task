<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Daily Task - Bank DP Taspen</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 5rem;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo {
            max-width: 300px;
            height: auto;
        }
        .welcome-title {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .welcome-description {
            color: #7f8c8d;
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
        }
        .btn-login {
            background-color: #0056b3;
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 500;
            display: block;
            margin: 0 auto;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: #003d7a;
            transform: translateY(-2px);
        }
        .footer {
            text-align: center;
            margin-top: 3rem;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-container">
            <div class="logo-container">
                <img src="https://bankdptaspen.co.id/wp-content/uploads/2024/01/Logo-Bank-DP-Taspen-Version-New.png" alt="Bank DP Taspen Logo" class="logo">
            </div>
            
            <h1 class="welcome-title">Selamat Datang di Aplikasi Daily Task Karyawan</h1>
            
            <p class="welcome-description">
                Aplikasi ini digunakan untuk mengelola tugas harian karyawan Bank DP Taspen. 
                Silahkan login menggunakan akun Anda untuk mengakses fitur-fitur aplikasi.
            </p>
            
            <a href="http://127.0.0.1:8000/admin/login" class="btn btn-primary btn-login">
                Login ke Aplikasi
            </a>
        </div>
        
        <div class="footer">
            &copy; 2024 Bank DP Taspen. Semua hak dilindungi.
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>