<?php
session_start();
require_once('conexao.php');

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header("Location: cardapio.php");
    exit;
}

// Aqui você desenvolveria a lógica para:
// 1. Inserir o pedido na tabela 'pedidos'
// 2. Inserir os itens na tabela 'itens_pedido'
// 3. Limpar o carrinho
// unset($_SESSION['carrinho']);

echo "<h1>Pedido recebido com sucesso!</h1>";
echo "<a href='cardapio.php'>Voltar ao início</a>";
?>