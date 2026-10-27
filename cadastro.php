<?php

session_start();

require_once __DIR__ . '/includes/funcoes.php';
require_once __DIR__ . '/config/conexao.php';

$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validarCsrf();

    $nome = trim((string) ($_POST['nome'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');

    if (
        $nome === ''
        || mb_strlen($nome) > 100
        || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 150
        || strlen($senha) < 8
    ) {
        $erro = 'Informe nome, e-mail válido e senha com no mínimo 8 caracteres.';
    } else {
        try {
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)'
            );
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $hash);
            $stmt->execute();

            $sucesso = true;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erro = 'E-mail já cadastrado.';
            } else {
                error_log($e->getMessage());
                $erro = 'Não foi possível cadastrar.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
</head>
<body>
    <h1>Criar conta</h1>

    <?php if ($erro) : ?>
        <p><?= escapar($erro) ?></p>
    <?php endif; ?>

    <?php if ($sucesso) : ?>
        <p>Conta criada! <a href="index.php">Faça login</a>.</p>
    <?php else : ?>
        <form method="post">
            <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">

            <label>Nome: <input name="nome" maxlength="100" required></label><br>

            <label>E-mail: <input type="email" name="email" maxlength="150" required></label><br>

            <label>
                Senha (mínimo 8 caracteres):
                <input type="password" name="senha" minlength="8" required>
            </label><br>

            <button type="submit">Cadastrar</button>
        </form>
    <?php endif; ?>

    <p><a href="index.php">Voltar</a></p>
</body>
</html>