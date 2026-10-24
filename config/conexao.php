<?php

$host = getenv('DB_HOST') ?: 'db';
$banco = getenv('DB_NAME') ?: 'gerenciador_tarefas';
$usuario = getenv('DB_USER') ?: 'app_user';
$senha = getenv('DB_PASSWORD');

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log('Erro PDO: ' . $e->getMessage());
    exit('Erro ao tentar conectar ao banco de dados.');
}
