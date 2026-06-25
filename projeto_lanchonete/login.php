<?php
session_start();
include_once('conexao.php');

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailDigitado = $_POST['email'];
    $senhaDigitada = $_POST['senha'];

    // 1. Buscamos o usuário pelo e-mail
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $emailDigitado]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Verificamos se o usuário existe e se a senha (hash) está correta
    if ($usuario && password_verify($senhaDigitada, $usuario['senha'])) {
        
        // 3. Sucesso! Guardamos os dados essenciais na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_cargo'] = $usuario['cargo']; // Aqui define se é 'admin' ou 'cliente'

        // 4. Redirecionamento Inteligente
        if ($_SESSION['usuario_cargo'] === 'admin') {
            // Se for o dono (admin), vai direto para a tela de gestão de pedidos
            header("Location: admin_pedidos.php");
        } else {
            // Se for cliente comum, vai para o cardápio principal
            header("Location: index.php");
        }
        exit;

    } else {
        // Erro genérico para não dar dicas a invasores
        $erro = "E-mail ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Lanchonete Express</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card-login { max-width: 380px; margin: 80px auto; border-radius: 15px; border: none; }
        .btn-entrar { background-color: #ffc107; font-weight: bold; border: none; transition: 0.3s; }
        .btn-entrar:hover { background-color: #e0a800; transform: translateY(-2px); }
        .logo-login { font-size: 3rem; text-align: center; display: block; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card card-login shadow-lg">
        <div class="card-body p-4">
            <span class="logo-login">🍔</span>
            <h3 class="text-center mb-4">Bem-vindo!</h3>

            <?php if($erro): ?>
                <div class="alert alert-danger py-2 text-center" style="font-size: 0.9rem;">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" placeholder="Sua senha secreta" required>
                </div>
                <button type="submit" class="btn btn-entrar w-100 mb-3 text-dark">ENTRAR NO SISTEMA</button>
                <div class="text-center">
                    <small class="text-muted">Ainda não tem conta? <a href="cadastro.php" class="text-warning fw-bold text-decoration-none">Criar uma agora</a></small>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>