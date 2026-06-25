<?php
session_start();
require_once('conexao.php');

// 1. Trava de segurança: só permite admin logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_cargo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. Verifica se o ID do produto foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Erro: O ID do produto não foi enviado na URL. Volte ao cardápio e clique em Editar novamente.");
}

$id = $_GET['id'];
$produto = null;

// 3. Busca os dados atuais do produto para preencher o formulário
try {
    $sql = "SELECT * FROM produtos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se o produto não existir no banco de dados
    if (!$produto) {
        $erro_critico = "Produto com ID #$id não foi encontrado no banco de dados.";
    }
} catch (PDOException $e) {
    $erro_critico = "Erro no banco de dados: " . $e->getMessage();
}

// 4. Processa o formulário quando o chefe envia os novos dados (Submete o POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $produto) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];

    if (!empty($nome) && !empty($preco) && !empty($categoria)) {
        try {
            $sql_update = "UPDATE produtos 
                           SET nome = :nome, descricao = :descricao, preco = :preco, categoria = :categoria 
                           WHERE id = :id";
            
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => $preco,
                ':categoria' => $categoria,
                ':id' => $id
            ]);

            header("Location: cardapio.php");
            exit;

        } catch (PDOException $e) {
            $erro = "Erro ao atualizar produto: " . $e->getMessage();
        }
    } else {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">🍔 LANCHONETE EXPRESS - PAINEL ADMIN</span>
        <a href="cardapio.php" class="btn btn-outline-light btn-sm">Voltar ao Cardápio</a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-3">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Editar Produto</h2>

                    <?php if (isset($erro_critico)): ?>
                        <div class="alert alert-danger text-center">
                            <h5>⚠️ Atenção!</h5>
                            <?= $erro_critico ?>
                            <div class="mt-3">
                                <a href="cardapio.php" class="btn btn-secondary btn-sm">Voltar ao Cardápio</a>
                            </div>
                        </div>
                    <?php else: ?>

                        <?php if (isset($erro)): ?>
                            <div class="alert alert-danger"><?= $erro ?></div>
                        <?php endif; ?>

                        <form action="editar_produto.php?id=<?= $id ?>" method="POST">
                            
                            <div class="mb-3">
                                <label for="nome" class="form-label fw-bold">Nome do Produto *</label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       value="<?= htmlspecialchars($produto['nome']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="categoria" class="form-label fw-bold">Categoria *</label>
                                <input type="text" class="form-control" id="categoria" name="categoria" 
                                       value="<?= htmlspecialchars($produto['categoria']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="preco" class="form-label fw-bold">Preço (R$) *</label>
                                <input type="number" class="form-control" id="preco" name="preco" step="0.01" 
                                       value="<?= $produto['preco'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="descricao" class="form-label fw-bold">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($produto['descricao']) ?></textarea>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-warning fw-bold">Salvar Alterações</button>
                                <a href="cardapio.php" class="btn btn-outline-secondary">Cancelar</a>
                            </div>

                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>