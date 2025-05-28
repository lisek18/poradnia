<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (isset($_SESSION['user_id']) && isset($_GET['seen'])) {
  $patient_id = $_SESSION['user_id'];
  $max_id = $mysqli->query("SELECT MAX(id) AS max_id FROM news")->fetch_assoc()['max_id'];
  $mysqli->query("UPDATE patients SET last_seen_news_id = $max_id WHERE id = $patient_id");
}


$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$news = [];

$result = $mysqli->query("SELECT title, content, published_at FROM news ORDER BY published_at DESC");
while ($row = $result->fetch_assoc()) {
  $news[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Aktualności – MediClinic</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" href="image/favicon.png" />
  <style>
    .news-container {
      max-width: 1100px;
      margin: 4rem auto;
      padding: 2rem;
    }
    .news-container h2 {
      color: #0a9396;
      text-align: center;
      margin-bottom: 2rem;
    }
    .news-item {
      border-bottom: 1px solid #ccc;
      padding: 1.5rem 0;
    }
    .news-item h3 {
      color: #005f73;
      margin-bottom: 0.5rem;
    }
    .news-item .date {
      font-size: 0.9rem;
      color: #777;
      margin-bottom: 0.5rem;
    }
    .news-item p {
      font-size: 1rem;
      line-height: 1.5;
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="news-container">
  <h2>Aktualności z poradni</h2>

  <?php foreach ($news as $item): ?>
    <div class="news-item">
      <h3><?= htmlspecialchars($item['title']) ?></h3>
      <div class="date"><?= date("d.m.Y", strtotime($item['published_at'])) ?></div>
      <p><?= nl2br(htmlspecialchars($item['content'])) ?></p>
    </div>
  <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
