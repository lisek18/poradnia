<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['doctor_id'])) {
  header("Location: login_doctor.php");
  exit;
}

$doctor_id = $_SESSION['doctor_id'];
$patient_id = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : null;

// Obsługa wysyłania wiadomości
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $patient_id) {
  $msg = trim($_POST['message']);
  if (!empty($msg)) {
    $stmt = $mysqli->prepare("INSERT INTO messages (sender_id, receiver_id, sender_role, message) VALUES (?, ?, 'doctor', ?)");
    $stmt->bind_param("iis", $doctor_id, $patient_id, $msg);
    $stmt->execute();
  }
}

// Lista pacjentów, z którymi lekarz rozmawiał
$patients = $mysqli->query("
  SELECT DISTINCT p.id, p.first_name, p.last_name
  FROM patients p
  JOIN messages m ON (
    (m.sender_id = p.id AND m.receiver_id = $doctor_id) OR 
    (m.receiver_id = p.id AND m.sender_id = $doctor_id)
  )
");

$patients = $mysqli->query("SELECT id, first_name, last_name FROM patients");



// Historia wiadomości (jeśli wybrano pacjenta)
$messages = [];
if ($patient_id) {
  $stmt = $mysqli->prepare("
    SELECT * FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?)
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY sent_at ASC
  ");
  $stmt->bind_param("iiii", $doctor_id, $patient_id, $patient_id, $doctor_id);
  $stmt->execute();
  $messages = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Czat z pacjentem – Lekarz</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .chat-container {
      max-width: 1000px;
      margin: 4rem auto;
      display: flex;
      gap: 2rem;
    }
    .chat-sidebar {
      width: 250px;
      border-right: 1px solid #ccc;
      padding-right: 1rem;
    }
    .chat-messages {
      flex: 1;
    }
    .message {
      margin-bottom: 1rem;
    }
    .message.doctor { text-align: right; }
    .message.patient { text-align: left; }
  </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="chat-container">
  <div class="chat-sidebar">
    <h3>Pacjenci</h3>
    <ul>
      <?php while ($p = $patients->fetch_assoc()): ?>
        <li><a href="?patient_id=<?= $p['id'] ?>"><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></a></li>
      <?php endwhile; ?>
    </ul>
  </div>

  <div class="chat-messages">
    <h3>Rozmowa z pacjentem <?= $patient_id ? "#$patient_id" : '' ?></h3>

    <?php if ($patient_id): ?>
      <div style="max-height:400px; overflow-y:auto; margin-bottom:2rem; padding:1rem; border:1px solid #ddd;">
        <?php foreach ($messages as $msg): ?>
          <div class="message <?= $msg['sender_role'] ?>">
            <p><strong><?= ucfirst($msg['sender_role']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?></p>
            <small><?= $msg['sent_at'] ?></small>
          </div>
        <?php endforeach; ?>
      </div>

      <form method="POST">
        <textarea name="message" rows="3" style="width:100%;" placeholder="Odpowiedz pacjentowi..."></textarea><br>
        <button type="submit">Wyślij</button>
      </form>
    <?php else: ?>
      <p>Wybierz pacjenta z listy po lewej stronie, aby rozpocząć rozmowę.</p>
    <?php endif; ?>
      <a href="/poradnia/doctor/doctor_panel.php" class="btn" style="margin-top:10px;">← Wróć do panelu</a>

  </div>
  
</div>
</body>
</html>
