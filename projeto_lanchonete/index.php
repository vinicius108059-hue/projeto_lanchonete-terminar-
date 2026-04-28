<?php
session_start();
include_once('conexao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanchonete Express | Início</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .hero { 
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('img/background-lanches.jpg'); 
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0; 
            border-bottom: 5px solid #ffc107; 
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .btn-order { 
            background-color: #ffc107; 
            font-weight: bold; 
            color: #000; 
            padding: 15px 30px; 
            font-size: 1.2rem; 
        }

        .btn-order:hover { 
            background-color: #e0a800; 
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">🍔 Lanchonete Express</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center">

                <!-- MENU CARDÁPIO CONTROLADO -->
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <a class="nav-link" href="cardapio.php">Ver Cardápio</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Ver Cardápio</a>
                <?php endif; ?>

                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <span class="nav-link text-white me-3">
                        Olá, <?= $_SESSION['usuario_nome']; ?>!
                    </span>
                    <a class="btn btn-outline-danger btn-sm" href="logout.php">Sair</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                    <a class="btn btn-warning btn-sm ms-lg-2" href="cadastro.php">Cadastrar</a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>

<header class="hero text-center">
    <div class="container">
        <h1 class="display-2 fw-bold">Bateu a fome?</h1>
        <p class="lead fs-3">Os melhores lanches da cidade, entregues rapidinho na sua casa.</p>

        <div class="mt-5">

            <!-- BOTÃO CONTROLADO -->
            <?php if(isset($_SESSION['usuario_id'])): ?>

                <a href="cardapio.php" class="btn btn-order shadow-lg">
                    PEDIR AGORA
                </a>

            <?php else: ?>

                <a href="login.php" class="btn btn-order shadow-lg">
                    FAZER LOGIN PARA PEDIR
                </a>

            <?php endif; ?>

        </div>
    </div>
</header>

<section class="container my-5 text-center">
    <div class="row">
        <div class="col-md-4">
            <h3>🚀 Entrega Rápida</h3>
            <p>Seu lanche quentinho em menos de 40 minutos.</p>
        </div>
        <div class="col-md-4">
            <h3>🥩 Carne Premium</h3>
            <p>Blends artesanais feitos diariamente.</p>
        </div>
        <div class="col-md-4">
            <h3>🥤 Combos</h3>
            <p>As melhores promoções com batata e refri.</p>
        </div>
    </div>
</section>

<footer class="bg-dark text-white text-center py-4">
    <p>&copy; 2026 Lanchonete Express</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>