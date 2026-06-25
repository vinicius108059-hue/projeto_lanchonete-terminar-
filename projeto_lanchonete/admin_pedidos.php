<?php
session_start();
require_once('conexao.php');

// 1. SEGURANÇA: Bloqueia quem não é admin
if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. LÓGICA: Atualizar Status do Pedido
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $novoStatus = $_GET['status'];
    
    $sql = "UPDATE pedidos SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['status' => $novoStatus, 'id' => $id]);
    
    header("Location: admin_pedidos.php?msg=sucesso");
    exit;
}

// 3. BUSCA: Ajustado para usar id_usuario (o nome mais comum para essa coluna)
// Se der erro de novo, mude 'p.id_usuario' para 'p.usuario'
$sql = "SELECT p.*, u.nome as cliente 
        FROM pedidos p 
        JOIN usuarios u ON p.id_usuario = u.id 
        ORDER BY p.id DESC";

try {
    $pedidos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Caso ainda dê erro de coluna, ele vai te avisar aqui sem travar a tela branca
    die("Erro ao carregar pedidos: Verifique se o nome da coluna na tabela pedidos é id_usuario ou usuario_id. Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin | Lanchonete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-stats { border-radius: 10px; border: none; transition: 0.3s; }
        .card-stats:hover { transform: translateY(-5px); }
        .badge-status { font-size: 0.85rem; padding: 0.5em 0.8em; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
    <div class="container">
        <a class="navbar-brand" href="#">👨‍🍳 Painel do Chef</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text me-3 text-white d-none d-md-inline">Olá, <?= $_SESSION['usuario_nome'] ?>!</span>
            <a href="index.php" class="btn btn-outline-warning btn-sm me-2">Ver Cardápio</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-4 text-center">
        <div class="col-md-6 mb-3">
            <div class="card card-stats bg-white p-3 shadow-sm">
                <h6 class="text-muted">Total de Pedidos</h6>
                <h3 class="text-primary"><?= count($pedidos) ?></h3>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card card-stats bg-white p-3 shadow-sm">
                <h6 class="text-muted">Faturamento Bruto</h6>
                <?php $totalVendas = array_sum(array_column($pedidos, 'total')); ?>
                <h3 class="text-success">R$ <?= number_format($totalVendas, 2, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm p-3">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-center">Ações</th>
                </tr>