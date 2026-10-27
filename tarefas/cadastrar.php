<?php

require_once __DIR__ . '/../includes/autenticacao.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../config/conexao.php';

$erro = '';
$statusValidos = ['pendente', 'em andamento', 'concluida'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validarCsrf();
    $titulo = trim((string) ($_POST['titulo'] ?? ''));
    $descricao = trim((string) ($_POST['descricao'] ?? ''));
    $status = (string) ($_POST['status'] ?? '');

    if ($titulo === '' || mb_strlen($titulo) > 150 || !in_array($status, $statusValidos, true)) {
        $erro = 'Informe um título válido e um status permitido.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO tarefas (usuario_id, titulo, descricao, status) VALUES (:usuario_id, :titulo, :descricao, :status)');
        $stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
        $stmt->bindValue(':titulo', $titulo);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':status', $status);
        $stmt->execute();
        header('Location: listar.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova tarefa</title>
</head>
<body>
    <h1>Cadastrar tarefa</h1>

    <?php if ($erro) : ?>
        <p style="color: red;"><?= escapar($erro) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
        
        <label>
            Título: 
            <input name="titulo" maxlength="150" required value="<?= escapar($_POST['titulo'] ?? '') ?>">
        </label>
        <br><br>

        <label>
            Descrição: 
            <textarea name="descricao"><?= escapar($_POST['descricao'] ?? '') ?></textarea>
        </label>
        <br><br>

        <label>
            Status: 
            <select name="status">
                <option value="pendente" <?= (($_POST['status'] ?? '') === 'pendente') ? 'selected' : '' ?>>Pendente</option>
                <option value="em andamento" <?= (($_POST['status'] ?? '') === 'em andamento') ? 'selected' : '' ?>>Em andamento</option>
                <option value="concluida" <?= (($_POST['status'] ?? '') === 'concluida') ? 'selected' : '' ?>>Concluída</option>
            </select>
        </label>
        <br><br>

        <button type="submit">Salvar</button>
    </form>

    <p>
        <a href="listar.php">Voltar</a>
    </p>
</body>
</html>
