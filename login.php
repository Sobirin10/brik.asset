<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Manajemen Aset</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #c2e9fb 0%, #a1c4fd 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #444;
            overflow: hidden;
        }

        .login-container {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            width: 400px;
            transition: box-shadow 0.3s;
            position: relative;
        }

        .login-container:hover {
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.2);
        }

        .logo {
            height: 80px;
            margin-bottom: 20px;
        }

        h1 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s, transform 0.3s;
            position: relative;
            overflow: hidden;
        }

        button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            transition: width 0.5s, height 0.5s, top 0.5s, left 0.5s;
            z-index: 0;
            transform: translate(-50%, -50%) scale(0);
        }

        button:hover::after {
            width: 400%;
            height: 400%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(1);
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .moving-text {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }

        .social-icons {
            margin-top: 20px;
        }

        .social-icons i {
            margin: 0 10px;
            font-size: 30px;
            color: #007bff;
            transition: color 0.3s;
            cursor: pointer;
        }

        .social-icons i:hover {
            color: #0056b3;
        }

        .brick-icon {
            position: relative;
            animation: fly 0.5s forwards;
        }

        @keyframes fly {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-100px);
            }
            100% {
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .login-container {
                width: 90%;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="assets/images/logo.svg" alt="Logo" class="logo">
        <h1>Aplikasi Manajemen Aset</h1>
        <form method="POST" action="your_login_action.php">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p class="moving-text">Lupa username/password? Silahkan hubungi Admin IT Anda.</p>
    </div>
   <script>
        function flyIcon(icon) {
            icon.classList.add('fly');
            setTimeout(() => {
                icon.classList.remove('fly');
            }, 500);
        }
    </script>
</body>
</html>
