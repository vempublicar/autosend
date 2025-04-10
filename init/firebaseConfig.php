<?php
// firebaseConfig.php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

// Carregar variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configurações do Firebase
$firebaseConfig = [
    'apiKey' => $_ENV['FIREBASE_API_KEY'], // Sua chave de API do Firebase
    'authDomain' => $_ENV['FIREBASE_AUTH_DOMAIN'], // O domínio de autenticação do Firebase
    'databaseURL' => $_ENV['FIREBASE_DATABASE_URL'], // A URL do banco de dados do Firebase
    'projectId' => $_ENV['FIREBASE_PROJECT_ID'], // O ID do projeto
    'storageBucket' => $_ENV['FIREBASE_STORAGE_BUCKET'], // O bucket de armazenamento
    'messagingSenderId' => $_ENV['FIREBASE_MESSAGING_SENDER_ID'], // O ID do remetente de mensagens
    'appId' => $_ENV['FIREBASE_APP_ID'], // O ID da aplicação
];

$factory = (new Factory)
    ->withServiceAccount($firebaseConfig)
    ->withDatabaseUri($firebaseConfig['databaseURL']);

$auth = $factory->createAuth();

?>