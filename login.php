<?php

session_start();

require_once __DIR__ . '/includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

validarCsrf();

require_once __DIR__ . '/config/conexao.php';

$email = trim((string) ($_POST['email'] ?? ''));
$senha = (string) ($_POST['senha'] ?? '');

$stmt = $pdo->prepare('SELECT id, nome, senha FROM usuarios WHERE email = :email LIMIT 1');
$stmt->bindValue(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch();

if ($usuario && password_verify($senha, $usuario['senha'])) {
    session_regenerate_id(true);

    $_SESSION['usuario_id'] = (int) $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    unset($_SESSION['csrf']);

    header('Location: painel.php');
    exit;
}

header('Location: index.php?erro=1');
exit;
