<?php

require_once __DIR__ . '/../includes/autenticacao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}

validarCsrf();

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('ID inválido.');
}

require_once __DIR__ . '/../config/conexao.php';

$stmt = $pdo->prepare('DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
$stmt->execute();

header('Location: listar.php');
exit;
