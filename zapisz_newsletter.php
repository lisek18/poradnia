<?php
// Połączenie z bazą danych
$host = 'localhost'; // lub inny host np. 127.0.0.1
$db = 'mediclinic';
$user = 'root'; // dostosuj do swojego użytkownika
$pass = '';     // dostosuj do swojego hasła
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if ($email) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO newsletter_subscribers (email) VALUES (:email)");
        $stmt->execute(['email' => $email]);
        header("Location: index.php?newsletter=success");
    } else {
        header("Location: index.php?newsletter=error");
    }
}
?>
