<?php
session_start();
require_once('conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

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
<title>Cardápio Digital</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background-color: #f4f7f6; }

.card-produto { 
    border: none; 
    border-radius: 15px; 
    transition: 0.3s; 
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
}
</style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
<div class="container">
    <span class="navbar-brand">🍔 LANCHONETE EXPRESS</span>

    <div>
        <span class="text-light me-3 small">
            Olá, <?= $_SESSION['usuario_nome'] ?>
        </span>

        <?php if ($_SESSION['usuario_cargo'] === 'admin'): ?>
            <a href="cadastrar_produto.php" class="btn btn-warning btn-sm">Gerenciar</a>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
    </div>
</div>
</nav>

<div class="container py-5">

<h1 class="text-center mb-4">Cardápio</h1>

<?php $categoriaAtual = ""; ?>

<div class="row">

<?php foreach ($produtos as $p): ?>

    <?php if ($p['categoria'] !== $categoriaAtual): 
        $categoriaAtual = $p['categoria']; ?>
        
        <div class="col-12 mt-4">
            <h4 class="categoria-header"><?= $categoriaAtual ?></h4>
        </div>
        
    <?php endif; ?>

    <div class="col-md-4 mb-4">
        <div class="card card-produto h-100">
            <div class="card-body d-flex flex-column">

                <h5 class="fw-bold"><?= $p['nome'] ?></h5>

                <p class="text-muted small flex-grow-1">
                    <?= $p['descricao'] ?>
                </p>

                <div class="d-flex justify-content-between align-items-center mt-3">

                    <span class="preco">
                        R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                    </span>

                    <div class="d-flex gap-2">

                        <a href="carrinho.php?add=<?= $p['id'] ?>" 
                           class="btn btn-dark btn-sm">
                           Adicionar
                        </a>

                        <?php if ($_SESSION['usuario_cargo'] === 'admin'): ?>
                            <a href="excluir_produto.php?id=<?= $p['id'] ?>" 
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Excluir produto?')">
                               Excluir
                            </a>
                        <?php endif; ?>

                    </div>

                </div>

            </div>
        </div>
    </div>

<?php endforeach; ?>

<?php if (empty($produtos)): ?>
    <div class="text-center mt-5">
        <p>Nenhum produto cadastrado.</p>
    </div>
<?php endif; ?>

</div>
</div>

</body>
</html>