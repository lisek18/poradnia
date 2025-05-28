<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

$visit_id = (int)$_GET['id'];

// Pobierz wizytę oraz pacjenta
$visit = $mysqli->query("
  SELECT v.*, p.first_name, p.last_name, p.id AS patient_id, dep.name AS department_name 
  FROM visits v 
  JOIN patients p ON v.patient_id = p.id 
  JOIN departments dep ON v.department_id = dep.id
  WHERE v.id = $visit_id
")->fetch_assoc();
// Punkt B: dynamiczna nazwa tabeli usług
$department = strtolower($visit['department_name']); // np. "Ortopedia" → "ortopedia"
$department = str_replace(['ł', 'ś', 'ż', 'ź', 'ć', 'ę', 'ó', 'ń', 'ą'], 
                          ['l', 's', 'z', 'z', 'c', 'e', 'o', 'n', 'a'], $department);
$service_table = $department . "_services"; // np. ortopedia_services

// Punkt C: pobranie usług z właściwej tabeli
$services = $mysqli->query("SELECT id, service_name, price FROM `$service_table`");



$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $notes = $_POST['visit_notes'];
  $recommend = $_POST['recommendations'];
$service_id = !empty($_POST['service_id']) ? (int)$_POST['service_id'] : null;

$update = $mysqli->prepare("UPDATE visits SET visit_notes=?, recommendations=?, service_id=? WHERE id=?");
$update->bind_param("ssii", $notes, $recommend, $service_id, $visit_id);
$update->execute();



  // Wgrywanie dokumentu
  if (!empty($_FILES['document']['name'])) {
    $patient_id = $visit['patient_id'];
    $file = $_FILES['document'];

    // Utwórz folder pacjenta jeśli nie istnieje
    $upload_dir = __DIR__ . "/../uploads/patient_$patient_id/";
    if (!file_exists($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    $file_name = basename($file['name']);
    $relative_path = "/poradnia/uploads/patient_$patient_id/" . $file_name;
    $target_path = $upload_dir . $file_name;
    $file_type = mime_content_type($file['tmp_name']);

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
      $insert = $mysqli->prepare("
        INSERT INTO medical_documents (patient_id, file_name, file_path, file_type) 
        VALUES (?, ?, ?, ?)
      ");
      $insert->bind_param("isss", $patient_id, $file_name, $relative_path, $file_type);
      $insert->execute();
    } else {
      $error = "Nie udało się zapisać pliku.";
    }
  }

  if (empty($error)) {
    $success = "Dane wizyty zostały zaktualizowane.";
  }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Edycja wizyty</title>
  <link rel="stylesheet" href="../css/style.css">
  
</head>
<body>
<?php include 'header.php'; ?>
<div class="container" style="max-width: 900px; margin-top: 3rem;">
  <h2>Wizyta: <?= htmlspecialchars($visit['first_name'] . ' ' . $visit['last_name']) ?> (<?= $visit['visit_datetime'] ?>)</h2>

  <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

  <form method="POST" enctype="multipart/form-data" style="margin-top:2rem;">
    

<label>Wykonane badanie:<br>
  <select name="service_id">
    <option value="">-- wybierz badanie --</option>
    <?php while ($srv = $services->fetch_assoc()): ?>
      <option value="<?= $srv['id'] ?>" <?= ($visit['service_id'] == $srv['id'] ? 'selected' : '') ?>>
        <?= htmlspecialchars($srv['service_name']) ?> (<?= $srv['price'] ?> zł)
      </option>
    <?php endwhile; ?>
  </select>
</label><br><br>


    <label>Opis wizyty:<br>
      <textarea name="visit_notes" rows="5" style="width:100%;"><?= htmlspecialchars($visit['visit_notes']) ?></textarea>
    </label><br><br>

    <label>Zalecenia:<br>
      <textarea name="recommendations" rows="4" style="width:100%;"><?= htmlspecialchars($visit['recommendations']) ?></textarea>
    </label><br><br>

    <label>Dodaj dokument:
      <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.docx,.txt">
    </label><br><br>

    <button type="submit" class="btn">Zapisz</button>
  </form>
   <a href="/poradnia/doctor/doctor_panel.php" class="btn" style="margin-top:10px;">← Wróć do panelu</a>
</div>

</body>
</html>
