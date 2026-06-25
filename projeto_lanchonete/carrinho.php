<?php
session_start();
require_once('conexao.php');

// 1. Lógica para ADICIONAR
if (isset($_GET['add'])) {
    $id_produto = $_GET['add'];
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }
    $_SESSION['carrinho'][] = $id_produto;
    header("Location: carrinho.php");
    exit;
}

// 1.1 NOVA LÓGICA PARA REMOVER UM ITEM
if (isset($_GET['remover'])) {
    $id_remover = $_GET['remover'];
    
    // Procura a primeira ocorrência do ID no array do carrinho
    if (isset($_SESSION['carrinho'])) {
        $posicao = array_search($id_remover, $_SESSION['carrinho']);
        
        // Se encontrar, remove apenas aquela posição (uma unidade)
        if ($posicao !== false) {
            unset($_SESSION['carrinho'][$posicao]);
            // Reorganiza os índices do array
            $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
        }
    }
    header("Location: carrinho.php");
    exit;
}

// 2. Lógica para LISTAR
$produtos_carrinho = [];
$total = 0;

if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    $ids_unicos = array_unique($_SESSION['carrinho']);
    $ids_string = implode(',', array_map('intval', $ids_unicos));
    
    $sql = "SELECT * FROM produtos WHERE id IN ($ids_string)";
    $stmt = $pdo->query($sql);
    $produtos_carrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho | Lanchonete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h2 class="mb-4">🛒 Itens no seu Pedido</h2>

            <?php if (empty($produtos_carrinho)): ?>
                <div class="alert alert-warning">
                    Seu carrinho está vazio! <br>
                    <a href="cardapio.php" class="alert-link">Clique aqui para ver o cardápio.</a>
                </div>
            <?php else: ?>
                <table class="table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Produto</th>
                            <th class="text-center">Quantidade</th>
                            <th class="text-end">Preço Unit.</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center">Ações</th> </tr>
                    </thead>
                    <tbody>
                     <tbody>
    <?php foreach ($produtos_carrinho as $item): 
        // Conta a quantidade atual deste item no carrinho
        $quantidade = array_count_values($_SESSION['carrinho'])[$item['id']];
        $subtotal = $item['preco'] * $quantidade;
        $total += $subtotal;
    ?>
    <tr>
        <td>
            <strong><?= $item['nome'] ?></strong><br>
            <small class="text-muted"><?= $item['categoria'] ?></small>
        </td>
        
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center gap-2">
                <a href="carrinho.php?remover=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" style="width: 30px;">-</a>
                
                <span class="fw-bold fs-5" style="min-width: 30px;"><?= $quantidade ?></span>
                
                <a href="carrinho.php?add=<?= $item['id'] ?>" class="btn btn-sm btn-outline-success" style="width: 30px;">+</a>
            </div>
        </td>

        <td class="text-end">R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
        <td class="text-end fw-bold">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
        
        <td class="text-center">
            <a href="carrinho.php?remover_tudo=<?= $item['id'] ?>" class="btn btn-link btn-sm text-danger text-decoration-none">
                Excluir Item
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fs-4 fw-bold">Total do Pedido:</td>
                            <td class="text-end fs-4 fw-bold text-success">R$ <?= number_format($total, 2, ',', '.') ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-between mt-4">
                    <a href="cardapio.php" class="btn btn-outline-secondary">Voltar ao Cardápio</a>
                    <a href="finalizar_pedido.php" class="btn btn-success btn-lg px-5">Finalizar Pedido</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>