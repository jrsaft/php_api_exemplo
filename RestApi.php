<?php
header("Content-Type: application/json");

// Configurações do banco (vêm do docker-compose)
$host = 'db';
$db   = 'meu_banco';
$user = 'usuario';
$pass = 'senha123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    // Cria a tabela se não existir (para facilitar seu deploy)
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(255), email VARCHAR(255))");
} catch (\PDOException $e) {
    echo json_encode(["error" => "Falha na conexão: " . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? explode('/', trim($_GET['path'], '/')) : [];

// Rotas
if ($method == 'GET' && count($path) == 1 && $path[0] == 'users') {
    $stmt = $pdo->query("SELECT * FROM users");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} elseif ($method == 'POST' && count($path) == 1 && $path[0] == 'users') {
    $input = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("INSERT INTO users (nome, email) VALUES (?, ?)");
    $stmt->execute([$input['nome'], $input['email']]);
    echo json_encode(["message" => "Usuário criado", "id" => $pdo->lastInsertId()]);

} elseif ($method == 'DELETE' && count($path) == 2 && $path[0] == 'users') {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$path[1]]);
    echo json_encode(["message" => "Usuário deletado"]);
} else {
    echo json_encode(["error" => "Rota não encontrada ou não implementada"]);
}