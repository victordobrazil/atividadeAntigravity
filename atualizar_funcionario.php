<?php
session_start();
if(!isset($_SESSION['logado']) || $_SESSION['logado'] !== true){
    header("Location: login.php");
    exit();
}
if($_SESSION['usuario_funcao'] !== 'gerente'){
    echo "<script>alert('Acesso negado. Apenas gerentes podem acessar esta área.'); window.location.href = 'index_funcionario.php';</script>";
    exit();
}
include_once("objeto/funcionarioController.php");

$controller = new FuncionarioController();

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["alterar"])) {
    $a = $controller->localizarFuncionario($_GET["alterar"]);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["funcionario"])) {
    $controller->atualizarFuncionario($_POST["funcionario"]);
} else {
    header("Location: index_funcionario.php");
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atualizar Funcionário</title>
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
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%); color: var(--text-primary); position: relative; overflow-x: hidden; padding: 2rem; }
        body::before { content: ''; position: fixed; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 60%); animation: pulse-glow 15s ease-in-out infinite alternate; z-index: 0; pointer-events: none; }
        @keyframes pulse-glow { 0% { transform: scale(0.8) translate(5%, 5%); } 100% { transform: scale(1.1) translate(-5%, -5%); } }
        .blob { position: fixed; filter: blur(80px); z-index: 0; opacity: 0.4; border-radius: 50%; animation: float 10s infinite ease-in-out alternate; }
        .blob-1 { top: -10%; right: -5%; width: 400px; height: 400px; background: #818cf8; animation-delay: 0s; }
        .blob-2 { bottom: -15%; left: -10%; width: 500px; height: 500px; background: #4f46e5; animation-delay: -5s; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-30px, 30px) scale(1.1); } }
        @keyframes slide-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .form-container { width: 100%; max-width: 500px; padding: 2.5rem; background: var(--glass-bg); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); z-index: 1; animation: slide-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .header-title { font-size: 1.8rem; font-weight: 700; background: linear-gradient(to right, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 0.5rem; text-align: center; }
        .header-desc { color: var(--text-secondary); text-align: center; margin-bottom: 2rem; font-size: 0.95rem; }

        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
        .form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0, 0, 0, 0.2); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-primary); font-size: 1rem; outline: none; transition: all 0.3s ease; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2); background: rgba(0, 0, 0, 0.4); }
        
        .btn-submit { width: 100%; padding: 1rem; background: var(--primary); color: white; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-top: 1rem; box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.39); }
        .btn-submit:hover { background: var(--primary-hover); transform: translateY(-2px); }
        .btn-back { display: block; text-align: center; margin-top: 1.5rem; color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; transition: color 0.3s; }
        .btn-back:hover { color: white; text-decoration: underline; }

        .row-group { display: flex; gap: 1rem; }
        .row-group .form-group { flex: 1; }
    </style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="form-container">
    <h1 class="header-title">Atualizar Funcionário</h1>
    <p class="header-desc">Edite o perfil do colaborador #<?= $a->id ?></p>

    <form action="atualizar_funcionario.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="funcionario[id]" value="<?= $a->id ?>">
        
        <div class="form-group">
            <label>Nome Completo</label>
            <input type="text" name="funcionario[nome]" class="form-control" value="<?= trim($a->nome ?? '') ?>" required autocomplete="off">
        </div>
        
        <div class="row-group">
            <div class="form-group">
                <label>CPF</label>
                <input type="text" name="funcionario[cpf]" class="form-control" value="<?= trim($a->cpf ?? '') ?>" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Telefone</label>
                <input type="text" name="funcionario[telefone]" class="form-control" value="<?= trim($a->telefone ?? '') ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Endereço</label>
            <input type="text" name="funcionario[endereco]" class="form-control" value="<?= trim($a->endereco ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Função</label>
            <input type="text" name="funcionario[funcao]" class="form-control" value="<?= trim($a->funcao ?? '') ?>" required>
        </div>

        <div class="row-group">
            <div class="form-group">
                <label>Login</label>
                <input type="text" name="funcionario[login]" class="form-control" value="<?= trim($a->login ?? '') ?>" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="funcionario[senha]" class="form-control" placeholder="Deixe em branco para manter a atual">
            </div>
        </div>

        <div class="form-group">
            <label>Nova Foto de Perfil (Opcional)</label>
            <input type="file" name="imagem" accept="image/*" class="form-control" style="padding: 0.6rem 1rem;">
        </div>

        <button type="submit" name="atualizar" class="btn-submit">Salvar Alterações</button>
    </form>
    
    <a href="index_funcionario.php" class="btn-back">← Cancelar e Voltar</a>
</div>

</body>
</html>
