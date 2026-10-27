<?php
require_once __DIR__ . '/../includes/autenticacao.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../config/conexao.php';

$id = filter_var($_POST['id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('ID inválido.');
}

$usuarioId = $_SESSION['usuario_id'] ?? null;
$erro = '';
$statusValidos = ['pendente', 'em andamento', 'concluida'];

try {
    // Busca inicial da tarefa
    $stmt = $pdo->prepare('SELECT id, titulo, descricao, status FROM tarefas WHERE id = :id AND usuario_id = :usuario_id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
    $stmt->execute();
    $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tarefa) {
        http_response_code(404);
        exit('Tarefa não encontrada.');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        validarCsrf();
        $titulo = trim((string)($_POST['titulo'] ?? ''));
        $descricao = trim((string)($_POST['descricao'] ?? ''));
        $status = (string)($_POST['status'] ?? '');

        // Validações
        if ($titulo === '' || mb_strlen($titulo) > 150 || !in_array($status, $statusValidos, true)) {
            $erro = 'Informe um título válido (até 150 caracteres) e um status permitido.';
            // Mantém os dados digitados para o usuário corrigir
            $tarefa = ['id' => $id, 'titulo' => $titulo, 'descricao' => $descricao, 'status' => $status];
        } else {
            $updateStmt = $pdo->prepare('UPDATE tarefas SET titulo = :titulo, descricao = :descricao, status = :status WHERE id = :id AND usuario_id = :usuario_id');
            $updateStmt->bindValue(':titulo', $titulo);
            $updateStmt->bindValue(':descricao', $descricao);
            $updateStmt->bindValue(':status', $status);
            $updateStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $updateStmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $updateStmt->execute();

            header('Location: listar.php');
            exit;
        }
    }
} catch (PDOException $e) {
    // Em produção, registre o erro em log em vez de exibi-lo na tela
    $erro = 'Ocorreu um erro ao processar a solicitação. Tente novamente mais tarde.';
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
</head>
<body>
    <h1>Editar Tarefa</h1>

    <?php if ($erro) : ?>
        <p style="color: red;"><?= escapar($erro) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
        <input type="hidden" name="id" value="<?= (int)$id ?>">

        <div>
            <label for="titulo">Título:</label><br>
            <input type="text" id="titulo" name="titulo" maxlength="150" required value="<?= escapar($tarefa['titulo']) ?>">
        </div>
        <br>

        <div>
            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao" rows="4"><?= escapar($tarefa['descricao']) ?></textarea>
        </div>
        <br>

        <div>
            <label for="status">Status:</label><br>
            <select id="status" name="status">
                <?php foreach ($statusValidos as $opcao) : ?>
                    <option value="<?= escapar($opcao) ?>" <?= $tarefa['status'] === $opcao ? 'selected' : '' ?>>
                        <?= escapar(ucfirst($opcao)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <button type="submit">Atualizar</button>
    </form>

    <p><a href="listar.php">Voltar</a></p>
</body>
</html>
