<?php

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2)); 
$dotenv->load();

$apiKey = $_ENV['FIREBASE_API_KEY'];

function verificarTokenOuRenovar()
{
    if (!isset($_SESSION['userToken']) || !isset($_SESSION['refreshToken'])) {
        return false; // não autenticado
    }

    $idToken = $_SESSION['userToken'];

    // 1. Verifica se o token ainda é válido
    $verifyUrl = 'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=' . $_ENV['FIREBASE_API_KEY'];

    $verifyData = json_encode(['idToken' => $idToken]);

    $ch = curl_init($verifyUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $verifyData,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $decoded = json_decode($response, true);

    if (isset($decoded['users'])) {
        return true; // token ainda é válido
    }

    // 2. Token expirado → tenta renovar com o refreshToken
    $refreshToken = $_SESSION['refreshToken'];

    $refreshData = json_encode([
        'grant_type' => 'refresh_token',
        'refresh_token' => $refreshToken,
    ]);

    $refreshUrl = 'https://securetoken.googleapis.com/v1/token?key=' . $_ENV['FIREBASE_API_KEY'];

    $ch = curl_init($refreshUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $refreshData,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['id_token'])) {
        // Atualiza a sessão
        $_SESSION['userToken'] = $data['id_token'];
        $_SESSION['refreshToken'] = $data['refresh_token'];

        return true;
    }

    // Se falhar a renovação, limpa sessão
    session_destroy();
    return false;
}
