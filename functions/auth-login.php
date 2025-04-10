<?php
session_start();
require_once '../vendor/autoload.php';
require_once 'create.php';

use Dotenv\Dotenv;

// Carrega .env
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$apiKey = $_ENV['FIREBASE_API_KEY'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $url = 'https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=' . $apiKey;

        $postData = json_encode([
            'email' => $email,
            'password' => $password,
            'returnSecureToken' => true
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $responseData = json_decode($response, true);
            if (isset($responseData['idToken'])) {
                $_SESSION['userToken'] = $responseData['idToken'];
                $_SESSION['refreshToken'] = $responseData['refreshToken']; // <- aqui
                $_SESSION['email'] = $responseData['email'];
                $_SESSION['msg'] = "Login realizado com sucesso!";
                checkCreate($responseData['email']); // <-- executar
                header("Location: ../dashboard");
                exit;
            } else {
                $_SESSION['msg'] = "Erro: " . ($responseData['error']['message'] ?? 'Erro desconhecido.');
                header("Location: ../login");
                exit;
            }
        } else {
            $_SESSION['msg'] = "Erro na requisição ao servidor Firebase.";
            header("Location: ../login");
            exit;
        }
    } else {
        $_SESSION['msg'] = "Email e senha são obrigatórios.";
        header("Location: ../login");
        exit;
    }
}
?>
