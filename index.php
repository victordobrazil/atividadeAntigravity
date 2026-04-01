<?php
session_start();
if(isset($_SESSION['logado']) && $_SESSION['logado'] === true){
    header("Location: index_produtos.php");
    exit();
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Início - Loja OF</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-gradient-start: #0f172a;
            --bg-gradient-end: #1e1b4b;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            color: var(--text-primary);
            position: relative;
            overflow: hidden;
        }

        /* Ambient background glow */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 60%);
            animation: pulse-glow 15s ease-in-out infinite alternate;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes pulse-glow {
            0% { transform: scale(0.8) translate(5%, 5%); }
            100% { transform: scale(1.1) translate(-5%, -5%); }
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-container {
            width: 100%;
            max-width: 500px;
            padding: 3.5rem 2.5rem;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            z-index: 1;
            animation: slide-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            text-align: center;
        }

        .welcome-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .welcome-header p {
            color: var(--text-secondary);
            font-size: 1.05rem;
            line-height: 1.5;
            margin-bottom: 3rem;
        }

        .btn-enter {
            display: inline-block;
            width: 100%;
            padding: 1.2rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.39);
        }

        .btn-enter:hover {
            background: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
        }

        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.5;
            border-radius: 50%;
            animation: float 10s infinite ease-in-out alternate;
        }

        .blob-1 { top: -10%; right: -5%; width: 400px; height: 400px; background: #818cf8; animation-delay: 0s; }
        .blob-2 { bottom: -15%; left: -10%; width: 500px; height: 500px; background: #4f46e5; animation-delay: -5s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-30px, 30px) scale(1.1); }
        }
    </style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="welcome-container">
    <div class="welcome-header">
        <h1>Loja OF</h1>
        <p>Bem-vindo ao portal corporativo. Acesse sua conta de funcionário para gerenciar os produtos e membros da equipe.</p>
    </div>

    <a href="login.php" class="btn-enter">Entrar no Sistema ➔</a>
</div>

</body>
</html>
