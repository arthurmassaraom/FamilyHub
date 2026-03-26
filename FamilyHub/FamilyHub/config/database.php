<?php
// =============================================
//  FamilyHub - Conexão com o Banco de Dados
//  As credenciais são lidas do arquivo .env
//  que NUNCA deve ser enviado ao repositório.
// =============================================

// Carrega o arquivo .env (sobe um nível: config/ → FamilyHub/)
$envFile = dirname(__DIR__) . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_name = $_ENV['DB_NAME'] ?? '';
$db_user = $_ENV['DB_USER'] ?? '';
$db_pass = $_ENV['DB_PASS'] ?? '';

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    $debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
    $msg   = $debug ? $e->getMessage() : 'Verifique as configurações do servidor.';

    die('<div style="text-align:center;padding:50px;font-family:Segoe UI,sans-serif;">
        <h2>⚠️ Erro de Conexão</h2>
        <p>Não foi possível conectar ao banco de dados.</p>
        <p style="color:#999;font-size:13px;">' . htmlspecialchars($msg) . '</p>
    </div>');
}
