<?php

require_once __DIR__ . '/src/database.php';
require_once __DIR__ . '/src/Models/Record.php';
require_once __DIR__ . '/src/Controllers/RecordController.php';
require_once __DIR__ . '/src/Views/RecordView.php';
require_once __DIR__ . '/vendor/autoload.php';

use Crud\Controllers\RecordController;

// Настройка соединения с БД через PDO [[14]]
$dsn = 'mysql:host=localhost;dbname=example1;charset=utf8mb4';
$username = 'root';
$password = ''; // По умолчанию в XAMPP пароль пустой

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    // Обработка GET-запроса
    $controller = new RecordController($pdo);
    $controller->index();

} catch (PDOException $e) {
    http_response_code(500);
    echo 'Ошибка подключения к БД: ' . htmlspecialchars($e->getMessage());
}

$controller = new RecordController();
$view = new RecordView();

$action = $_GET['action'] ?? 'index';
$page = (int)($_GET['page'] ?? 1);

switch ($action) {
    case 'index':
    default:
        $records = $controller->paginate($page);
        $totalPages = $controller->totalPages();
        echo $view->list($records, $page, $totalPages);
        break;

    case 'insert':
        echo $view->form();
        break;

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->insert($_POST);
            header('Location: ?action=index');
            exit;
        }
        break;

    case 'edit':
        $id = (int)($_GET['id'] ?? 0);
        echo $view->form($controller->show($id));
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $controller->update($id, $_POST);
            header('Location: ?action=index');
            exit;
        }
        break;

    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        $controller->delete($id);
        header('Location: ?action=index');
        exit;

    case 'restore':
        $id = (int)($_GET['id'] ?? 0);
        $controller->restore($id);
        header('Location: ?action=index');
        exit;
}
