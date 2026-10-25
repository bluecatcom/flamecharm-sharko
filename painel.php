<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/includes/funcoes.php';
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel</title>
</head>
<body>
    <h1>Painel</h1>

    <p>Olá, <?= escapar($_SESSION['usuario_nome']) ?>!</p>

    <p><a href="tarefas/listar.php">Gerenciar tarefas</a></p>

    <form action="logout.php" method="post">
        <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
        <button type="submit">Sair</button>
    </form>
</body>
</html>
