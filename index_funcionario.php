<?php
session_start();
if(!isset($_SESSION['logado']) || $_SESSION['logado'] !== true){
    header("Location: login.php");
    exit();
}
if($_SESSION['usuario_funcao'] !== 'gerente'){
    echo "<script>alert('Acesso negado. Apenas gerentes podem visualizar os funcionários.'); window.location.href = 'index_produtos.php';</script>";
    exit();
}
include_once"objeto/funcionarioController.php";

$controller = new FuncionarioController();
$funcionarios = $controller->index();
$a = null;

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["pesquisar"])){
        $valor = $_POST["pesquisar"];
        $tipo = $_POST["tipo"];
        $a = $controller->pesquisaFuncionario($valor, $tipo);
    }
}

if($_SERVER["REQUEST_METHOD"] === "GET"){
    if(isset($_GET["excluir"])){
        if($_SESSION['usuario_funcao'] === 'gerente'){
            $controller->excluirFuncionario($_GET["excluir"]);
        } else {
            echo "<script>alert('Acesso negado. Apenas gerentes podem excluir funcionários.'); window.location.href = 'index_funcionario.php';</script>";
            exit();
        }
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciar Funcionários</title>
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
            --success: #10b981;
            --danger: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            color: var(--text-primary);
            position: relative;
            padding: 2rem;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
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

        .blob {
            position: fixed;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.4;
            border-radius: 50%;
            animation: float 10s infinite ease-in-out alternate;
        }

        .blob-1 { top: -10%; right: -5%; width: 400px; height: 400px; background: #818cf8; animation-delay: 0s; }
        .blob-2 { bottom: -15%; left: -10%; width: 500px; height: 500px; background: #4f46e5; animation-delay: -5s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-30px, 30px) scale(1.1); }
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--glass-border);
        }

        h1, h2, h3, h4 { color: #fff; }

        header h1 {
            font-weight: 700;
            font-size: 1.8rem;
            background: linear-gradient(to right, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        nav {
            display: flex;
            gap: 1rem;
        }

        nav a, .btn {
            padding: 0.6rem 1.2rem;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 1px solid var(--glass-border);
        }

        nav a:hover, .btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        nav a.active {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.39);
        }

        .btn-primary { background: var(--primary); border: none; }
        .btn-primary:hover { background: var(--primary-hover); }

        .search-container {
            background: rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            border: 1px solid var(--glass-border);
        }

        .search-container form {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        input, select {
            padding: 0.7rem 1rem;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: white;
            outline: none;
            font-size: 0.9rem;
        }

        input:focus, select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        option { background: var(--bg-gradient-end); color: white; }

        .table-responsive {
            overflow-x: auto;
            margin-top: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--glass-border);
            background: rgba(0, 0, 0, 0.15);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--glass-border);
        }

        th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:hover td { background: rgba(255, 255, 255, 0.03); }

        .action-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            margin-right: 0.5rem;
            font-size: 0.85rem;
            transition: color 0.3s;
        }

        .action-links a.delete { color: var(--danger); }
        .action-links a:hover { text-decoration: underline; opacity: 0.8;}

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="dashboard-container">
    <header>
        <h1>Painel de Gerenciamento</h1>
        <nav style="align-items: center;">
            <div style="margin-right: 1rem; text-align: right; line-height: 1.2; padding-right: 1.5rem; border-right: 1px solid var(--glass-border);">
                <span style="font-size: 0.95rem; font-weight: 600; color: #f8fafc; display: block; margin-bottom: 2px;">Olá, <?= explode(' ', trim($_SESSION['usuario_nome']))[0] ?></span>
                <span style="font-size: 0.75rem; color: #a5b4fc; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;"><?= $_SESSION['usuario_funcao'] ?></span>
            </div>
            <a href="index_produtos.php">Produtos</a>
            <?php if($_SESSION['usuario_funcao'] === 'gerente'): ?>
            <a href="index_funcionario.php" class="active">Funcionários</a>
            <?php endif; ?>
            <a href="logout.php" style="background: rgba(239, 68, 68, 0.15); color: #fca5a5; border-color: rgba(239, 68, 68, 0.3);">Sair</a>
        </nav>
    </header>

    <div class="section-header">
        <h2>Funcionários Cadastrados</h2>
        <?php if($_SESSION['usuario_funcao'] === 'gerente'): ?>
        <a href="cadastro_funcionario.php" class="btn btn-primary">+ Novo Funcionário</a>
        <?php endif; ?>
    </div>

    <div class="search-container">
        <form method="POST" action="index_funcionario.php">
            <h4 style="margin-right: 1rem;">Pesquisar Funcionário:</h4>
            <input type="text" name="pesquisar" placeholder="Digite sua busca..." autocomplete="off">
            <select name="tipo">
                <option value="id">ID</option>
                <option value="nome">Nome</option>
            </select>
            <button class="btn btn-primary" style="padding: 0.7rem 1.5rem;">Buscar</button>
        </form>
    </div>

    <?php if($a) : ?>
    <h3 style="margin-top: 2rem;">Resultado da Busca</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Função</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($a as $func) : ?>
                <tr>
                    <td><?= $func->id; ?></td>
                    <td>
                        <img src="<?= !empty($func->imagem) ? 'uploads/' . $func->imagem : 'uploads/sem-foto.png' ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid var(--glass-border); background: #f8fafc; padding: 2px;">
                    </td>
                    <td><?= $func->nome; ?></td>
                    <td><?= $func->cpf; ?></td>
                    <td><span style="padding:4px 8px;background:rgba(255,255,255,0.1);border-radius:4px;font-size:0.8rem;"><?= $func->funcao;?></span></td>
                    <td class="action-links">
                        <?php if($_SESSION['usuario_funcao'] === 'gerente'): ?>
                            <a href="ver_funcionario.php?id=<?= $func->id ?>" style="color:#f8fafc; font-weight:600;">Ver</a>
                            <a href="atualizar_funcionario.php?alterar=<?= $func->id ?>">Alterar</a>
                            <a href="index_funcionario.php?excluir=<?= $func->id ?>" class="delete">Excluir</a>
                        <?php else: ?>
                            <span style="color:var(--text-secondary);font-size:0.85rem;">Acesso Restrito</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Lista Completa</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>Função</th>
                    <th>Login</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if($funcionarios) : ?>
                <?php foreach($funcionarios as $func) : ?>
                <tr>
                    <td><?= $func->id;?></td>
                    <td>
                        <img src="<?= !empty($func->imagem) ? 'uploads/' . $func->imagem : 'uploads/sem-foto.png' ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid var(--glass-border); background: #f8fafc; padding: 2px;">
                    </td>
                    <td><?= $func->nome;?></td>
                    <td><?= $func->cpf;?></td>
                    <td><?= $func->endereco;?></td>
                    <td><?= $func->telefone;?></td>
                    <td><span style="padding:4px 8px;background:rgba(255,255,255,0.1);border-radius:4px;font-size:0.8rem;"><?= $func->funcao;?></span></td>
                    <td><?= $func->login;?></td>
                    <td class="action-links">
                        <?php if($_SESSION['usuario_funcao'] === 'gerente'): ?>
                            <a href="ver_funcionario.php?id=<?= $func->id ?>" style="color:#f8fafc; font-weight:600;">Ver</a>
                            <a href="atualizar_funcionario.php?alterar=<?= $func->id ?>">Alterar</a>
                            <a href="index_funcionario.php?excluir=<?= $func->id ?>" class="delete">Excluir</a>
                        <?php else: ?>
                            <span style="color:var(--text-secondary);font-size:0.85rem;">Acesso Restrito</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
