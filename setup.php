<?php

$host = 'localhost';
$port = 3306;
$dbname = 'dbcrud';
$username = 'root';
$password = '';

// Подключение без БД для её создания
try {
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создание БД, если не существует
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Удаление и создание таблицы заново
$pdo->exec("DROP TABLE IF EXISTS records");
$pdo->exec("CREATE TABLE records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    deleted TINYINT(1) DEFAULT 0
)");

// Очистка таблицы
$pdo->exec("TRUNCATE TABLE records");

// Вставка 3 записей
$stmt = $pdo->prepare("INSERT INTO records (name, deleted) VALUES (?, 0)");
$stmt->execute(['Земля']);
$stmt->execute(['Луна']);
$stmt->execute(['Марс']);

// Добавление 20 записей
$planets = ['Меркурий', 'Венера', 'Сатурн', 'Юпитер', 'Уран', 'Нептун', 'Плутон', 'Эрида', 'Макемаке', 'Хаумеа', 
'Церера', 'Седна', 'Оркус', 'Квавар', 'Варуна', 'Иксион', 'Ваутор', 'Альчера', 'С/2010 U2', 'Комета Галлея'];

foreach ($planets as $planet) {
    $stmt->execute([$planet]);
}

echo "Таблица создана и заполнена!";

