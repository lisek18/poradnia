
<header class="header">
  <div class="container nav-container">
    <a href="/poradnia/index.php" class="logo"><img src="/poradnia/image/logo.png" alt="MediClinic" /></a>
    <nav class="main-nav">
      <a href="/poradnia/index.php">Strona Główna</a>

      <div class="dropdown">
        <a href="#">O nas</a>
        <div class="dropdown-content">
          <a href="/poradnia/about.php">O nas</a>
          <a href="/poradnia/department/specjalisci.php">Specjaliści</a>
        </div>
      </div>

      <div class="dropdown">
        <a href="/poradnia/department/services.php">Usługi</a>
        <div class="dropdown-content">
          <a href="/poradnia/department/ortopedia.php">Ortopedia</a>
          <a href="/poradnia/department/kardiologia.php">Kardiologia</a>
          <a href="/poradnia/department/dermatologia.php">Dermatologia</a>
          <a href="/poradnia/department/neurologia.php">Neurologia</a>
          <a href="/poradnia/department/pediatria.php">Pediatria</a>
          <a href="/poradnia/department/okulistyka.php">Okulistyka</a>
        </div>
      </div>
      <a href="/poradnia/news.php">Aktualności</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/poradnia/patient/profil.php">Mój profil</a>
        <a href="/poradnia/logout.php">Wyloguj</a>
      <?php else: ?>
        <a href="/poradnia/patient/login.php">Konto pacjenta</a>
      <?php endif; ?>

      <a href="/poradnia/kontakt.php">Kontakt</a>
    </nav>
  </div>
</header>
