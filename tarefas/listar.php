<?php

require_once __DIR__ . '/../includes/autenticacao.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../config/conexao.php';

$stmt = $pdo->prepare(
    'SELECT id, titulo, descricao, status, criado_em
     FROM tarefas
     WHERE usuario_id = :usuario_id
     ORDER BY id DESC'
);
$stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
$stmt->execute();

$tarefas = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Tarefas</title>
</head>
<body>
    <h1>Minhas tarefas</h1>

    <p>
        <a href="cadastrar.php">Nova tarefa</a> |
        <a href="../painel.php">Painel</a>
    </p>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Criada em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tarefas as $t) : ?>
                <tr>
                    <td><?= (int) $t['id'] ?></td>
                    <td><?= escapar($t['titulo']) ?></td>
                    <td><?= nl2br(escapar($t['descricao'])) ?></td>
                    <td><?= escapar($t['status']) ?></td>
                    <td><?= escapar($t['criado_em']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= (int) $t['id'] ?>">Editar</a>

                        <form action="excluir.php" method="post" 
                            onsubmit="return confirm('Deseja excluir esta tarefa?');">
                            <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
                            <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (!$tarefas) : ?>
        <p>Nenhuma tarefa cadastrada.</p>
    <?php endif; ?>
</body>
</html>