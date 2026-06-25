<?php
session_start();
require_once('conexao.php');

// 1. Verificação de segurança: Só admin pode deletar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_cargo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. Verifica se o ID foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM produtos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redireciona de volta para o cardápio com sucesso
        header("Location: cardapio.php?msg=sucesso");
        exit;
    } catch (PDOException $e) {
        die("Erro ao excluir produto: " . $e->getMessage());
    }
} else {
    header("Location: cardapio.php");
    exit;
}