<?php

function escapar($valor): string {
    return htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function tokenCsrf(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function validarCsrf(): void {
    if (!isset($_POST['csrf']) || !hash_equals(tokenCsrf(), (string)$_POST['csrf'])) {
        http_response_code(403);
        exit('Requisição inválida (CSRF).');
    }
}
