<?php
session_start();
require_once('conexao.php');

// Verifica se o usuário está logado para ver o cardápio
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// 1. Buscamos todos os produtos do banco
try {
    $sql = "SELECT * FROM produtos ORDER BY categoria ASC, nome ASC";
    $stmt = $pdo->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao carregar produtos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio Digital | Lanchonete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .card-produto { 
            border: none; 
            border-radius: 15px; 
            transition: 0.3s; 
            overflow: hidden;
        }
        .card-produto:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
        }
        .preco { 
            font-size: 1.25rem; 
            font-weight: bold; 
            color: #198754; 
        }
        .categoria-header {
            border-bottom: 2px solid #ffc107;
            display: inline-block;
            margin-bottom: 20px;
            padding-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">🍔 LANCHONETE EXPRESS</a>
        <div class="d-flex align-items-center">
            <span class="text-light me-3 small">Olá, <?= $_SESSION['usuario_nome'] ?></span>
            
            <?php if ($_SESSION['usuario_cargo'] === 'admin'): ?>
                <a href="cadastrar_produto.php" class="btn btn-warning btn-sm me-2">Gerenciar Itens</a>
            <?php endif; ?>
            
            <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Nosso Cardápio</h1>
        <p class="text-muted">Escolha as melhores delícias da região</p>
    </div>

    <?php
    // Lógica para agrupar por categoria visualmente
    $categoriaAtual = "";
    ?>

   <div class="row">
    <?php foreach ($produtos as $p): ?>
        <?php if ($p['categoria'] !== $categoriaAtual): 
            $categoriaAtual = $p['categoria']; ?>
            <div class="col-12 mt-4">
                <h3 class="categoria-header text-uppercase small"><?= $categoriaAtual ?></h3>
            </div>
        <?php endif; ?>

        <div class="col-md-4 mb-4">
            <div class="card card-produto h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="card-title fw-bold mb-1"><?= $p['nome'] ?></h5>
                    </div>
                    <p class="card-text text-muted small flex-grow-1">
                        <?= $p['descricao'] ?>
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="preco">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
                        
                        <a href="carrinho.php?add=<?= $p['id'] ?>" class="btn btn-dark btn-sm rounded-pill px-3">
                            Adicionar
                        </a>
                        <div class="d-flex justify-content-between align-items-center mt-3">
    <span class="preco">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
    
    <div class="d-flex gap-2"> <a href="carrinho.php?add=<?= $p['id'] ?>" class="btn btn-dark btn-sm rounded-pill px-3">
            Adicionar
        </a>

        <?php if ($_SESSION['usuario_cargo'] === 'admin'): ?>
            <a href="excluir_produto.php?id=<?= $p['id'] ?>" 
               class="btn btn-outline-danger btn-sm rounded-pill"
               onclick="return confirm('Tem certeza que deseja excluir este produto?')">
               Excluir
            </a>
        <?php endif; ?>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
        <?php if (empty($produtos)): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Nenhum item cadastrado no cardápio ainda.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer class="text-center py-4 text-muted">
    <small>&copy; 2026 Lanchonete Express - Sistema de Gestão</small>
</footer>

</body>
</html>