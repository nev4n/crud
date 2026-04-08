<?php

require_once __DIR__ . '/src/database.php';
require_once __DIR__ . '/src/Models/Record.php';
require_once __DIR__ . '/src/Controllers/RecordController.php';
require_once __DIR__ . '/src/Views/RecordView.php';

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
