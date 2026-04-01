<?php
session_start();
if(!isset($_SESSION['logado']) || $_SESSION['logado'] !== true){
    header("Location: login.php");
    exit();
}
include_once("objeto/produtosController.php");

if (!isset($_GET["id"])) {
    header("Location: index_produtos.php");
    exit();
}

$controller = new ProdutosController();
$produto = $controller->localizarProduto($_GET["id"]);

if (!$produto) {
    echo "<script>alert('Produto não encontrado!'); window.location.href = 'index_produtos.php';</script>";
    exit();
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalhes do Produto</title>
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
        body { min-height: 100vh; background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%); color: var(--text-primary); position: relative; padding: 2rem; overflow-x: hidden; }
        .blob { position: fixed; filter: blur(80px); z-index: 0; opacity: 0.4; border-radius: 50%; animation: float 10s infinite ease-in-out alternate; }
        .blob-1 { top: -10%; right: -5%; width: 400px; height: 400px; background: #818cf8; animation-delay: 0s; }
        .blob-2 { bottom: -15%; left: -10%; width: 500px; height: 500px; background: #4f46e5; animation-delay: -5s; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-30px, 30px) scale(1.1); } }
        .dashboard-container { max-width: 800px; margin: 0 auto; position: relative; z-index: 1; background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 24px; padding: 2.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        .btn { padding: 0.6rem 1.2rem; background: rgba(255, 255, 255, 0.1); color: var(--text-primary); text-decoration: none; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; border: 1px solid var(--glass-border); display: inline-block; margin-bottom: 2rem;}
        .btn:hover { background: rgba(255, 255, 255, 0.2); transform: translateY(-2px); }
        .header-title { font-size: 1.8rem; font-weight: 700; background: linear-gradient(to right, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 0.5rem; }
        .detail-card { background: rgba(0, 0, 0, 0.2); padding: 2rem; border-radius: 16px; border: 1px solid var(--glass-border); }
        .detail-row { display: flex; padding: 1.2rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 140px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; display:flex; align-items:center; }
        .detail-value { color: white; flex: 1; font-size: 1.1rem; }
    </style>
</head>
<body>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="dashboard-container">
    <a href="index_produtos.php" class="btn">← Voltar para Produtos</a>
    
    <h1 class="header-title">Detalhes do Produto #<?= $produto->id ?></h1>
    <p style="color: var(--text-secondary); margin-bottom: 2rem;">Visualizando informações completas de registro.</p>

    <div class="detail-card">
        <div style="text-align:center; margin-bottom:2rem;">
            <img src="<?= !empty($produto->imagem) ? 'uploads/' . $produto->imagem : 'uploads/sem-foto.png' ?>" style="width:150px; height:150px; border-radius:16px; object-fit:contain; border:2px solid var(--primary); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5); background: white; padding: 5px;">
        </div>
        <div class="detail-row">
            <div class="detail-label">ID</div>
            <div class="detail-value"><?= $produto->id ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Nome</div>
            <div class="detail-value"><?= $produto->nome ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Descrição</div>
            <div class="detail-value"><?= $produto->descricao ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Quantidade</div>
            <div class="detail-value"><?= $produto->quantidade ?> unidades disponíveis</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Preço</div>
            <div class="detail-value" style="color: #818cf8; font-weight: 600;">R$ <?= number_format($produto->preco, 2, ',', '.') ?></div>
        </div>
    </div>
</div>
</body>
</html>
