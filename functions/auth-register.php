<?php
session_start();
require_once '../vendor/autoload.php';

// Carregar variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__)); // sobe 1 nível
$dotenv->load();

$apiKey = $_ENV['FIREBASE_API_KEY'];
$url = "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=$apiKey";

// Caminho do seu banco SQLite (ajuste conforme necessário)
$dbPath = '../../database.sqlite';
$db = new SQLite3($dbPath);

// Validação básica
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    // Verifica se o e-mail existe na tabela membros
    $stmt = $db->prepare('SELECT COUNT(*) as total FROM membros WHERE LOWER(email) = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row['total'] == 0) {
        $_SESSION['msg'] = 'Você não está na lista de membros.';
        header('Location: ../register');
        exit;
    }

    // Se estiver na lista, continua com o registro no Firebase
    $postData = json_encode([
        'email' => $email,
        'password' => $password,
        'returnSecureToken' => true
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if (isset($responseData['idToken'])) {
        $_SESSION['userToken'] = $responseData['idToken'];
        $_SESSION['msg'] = "Usuário registrado com sucesso!";
        header('Location: ../dashboard');
        exit;
    } else {
        $_SESSION['msg'] = "Falha ao registrar: " . $responseData['error']['message'];
        header('Location: ../register');
        exit;
    }
} else {
    $_SESSION['msg'] = 'Preencha todos os campos corretamente.';
    header('Location: ../register');
    exit;
}
