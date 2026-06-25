<?php
session_start(); // 1. Conecta com a sessão atual
session_unset(); // 2. Remove as variáveis (nome, id, etc)
session_destroy(); // 3. Destrói a sessão completamente

// 4. Manda o usuário de volta para o cardápio
header("Location: index.php");
exit();
?>