<?php
include_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografia

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha])) {
        echo "<script>alert('Cadastro realizado!'); window.location='login.php';</script>";
    }
}
?>

<form method="POST" style="max-width: 300px; margin: 50px auto;">
    <h2>Criar Conta</h2>
    <input type="text" name="nome" placeholder="Nome completo" required class="form-control mb-2">
    <input type="email" name="email" placeholder="E-mail" required class="form-control mb-2">
    <input type="password" name="senha" placeholder="Senha" required class="form-control mb-2">
    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
</form>