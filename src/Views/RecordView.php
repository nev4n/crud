<?php

class RecordView
{
    public function list(array $records, int $currentPage = 1, int $totalPages = 1): string
    {
        $html = '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="container mt-4">
    <h1>Записи</h1>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>';

        if (empty($records)) {
            $html .= '<tr><td colspan="3" class="text-center">Нет записей</td></tr>';
        } else {
            foreach ($records as $r) {
                $isDeleted = $r->deleted;
                $rowClass = $isDeleted ? 'class="table-secondary"' : '';
                $nameClass = $isDeleted ? 'text-decoration-line-through text-muted' : '';
                
                $html .= '<tr ' . $rowClass . '>';
                $html .= '<td>' . $r->id . '</td>';
                $html .= '<td class="' . $nameClass . '">' . htmlspecialchars($r->name) . '</td>';
                $html .= '<td>';
                
                if ($isDeleted) {
                    $html .= '<a href="?action=restore&id=' . $r->id . '" class="btn btn-sm btn-success">Восстановить</a>';
                } else {
                    $html .= '<a href="?action=edit&id=' . $r->id . '" class="btn btn-sm btn-warning">Редактировать</a> ';
                    $html .= '<a href="?action=delete&id=' . $r->id . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Удалить?\')">Удалить</a>';
                }
                
                $html .= '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</tbody>
    </table>';

        // Пагинация
        $html .= $this->pagination($currentPage, $totalPages);

        $html .= '<a href="?action=insert" class="btn btn-primary mb-3">Добавить запись</a>
</body>
</html>';

        return $html;
    }

    private function pagination(int $currentPage, int $totalPages): string
    {
        if ($totalPages <= 1) return '';

        $html = '<nav><ul class="pagination justify-content-center">';

        // Первая страница
        if ($currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="?action=index&page=1"><i class="bi bi-chevron-double-left"></i></a></li>';
        } else {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-double-left"></i></a></li>';
        }

        // Предыдущая страница
        if ($currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="?action=index&page=' . ($currentPage - 1) . '"><i class="bi bi-chevron-left"></i></a></li>';
        } else {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>';
        }

        // Номера страниц
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="?action=index&page=' . $i . '">' . $i . '</a></li>';
            }
        }

        // Следующая страница
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="?action=index&page=' . ($currentPage + 1) . '"><i class="bi bi-chevron-right"></i></a></li>';
        } else {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>';
        }

        // Последняя страница
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="?action=index&page=' . $totalPages . '"><i class="bi bi-chevron-double-right"></i></a></li>';
        } else {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-double-right"></i></a></li>';
        }

        $html .= '</ul></nav>';

        return $html;
    }

    public function form(?Record $record = null): string
    {
        $id = $record ? $record->id : '';
        $name = $record ? htmlspecialchars($record->name) : '';
        $btn = $id ? 'Обновить' : 'Создать';
        $title = $id ? 'Редактирование' : 'Создание';
        $action = $id ? "?action=update&id=$id" : "?action=create";

        return "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$title</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='container mt-4'>
    <h1>$title</h1>
    <a href='?action=index' class='btn btn-secondary mb-3'>Назад</a>
    <form method='post' action='$action'>
        <div class='mb-3'>
            <label class='form-label'>Название</label>
            <input type='text' name='name' value='$name' class='form-control' required>
        </div>
        <button type='submit' class='btn btn-primary'>$btn</button>
    </form>
</body>
</html>";
    }

    private function error(string $message): string
    {
        return "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='container mt-4'>
    <div class='alert alert-danger'>$message</div>
    <a href='?action=index' class='btn btn-secondary'>Назад</a>
</body>
</html>";
    }
}
