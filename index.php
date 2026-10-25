<?php

session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: painel.php');
    exit;
}

require_once __DIR__ . '/includes/funcoes.php';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Gerenciador de Tarefas</h1>

    <?php if (isset($_GET['erro'])) : ?>
        <p>Usuário ou senha inválidos.</p>
    <?php endif; ?>

    <?php if (isset($_GET['saiu'])) : ?>
        <p>Sessão encerrada.</p>
    <?php endif; ?>

    <form action="login.php" method="post">
        <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">

        <label>E-mail: <input type="email" name="email" maxlength="150" required></label><br>

        <label>Senha: <input type="password" name="senha" required></label><br>

        <button type="submit">Entrar</button>
    </form>

    <p><a href="cadastro.php">Criar conta</a></p>
</body>
</html>