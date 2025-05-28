<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>O nas – MediClinic</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="image/favicon.png" />
</head>
<body>
<?php include 'includes/header.php'; ?>


  <section class="hero">
    <div class="container hero-content">
      <div class="hero-text">
        <h1>Poznaj nas bliżej</h1>
        <p>Jesteśmy zespołem specjalistów, którzy wierzą, że troska o zdrowie zaczyna się od zaufania i indywidualnego podejścia.</p>
      </div>
      <div class="hero-image">
        <img src="image/hero-image.webp" alt="Zespół lekarzy MediClinic" />
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <h2 class="section-title">Nasza misja</h2>
      <div class="mission-boxes">
        <div class="mission-box">
          <h3>Profesjonalizm</h3>
          <p>Dbamy o najwyższe standardy leczenia i komunikacji z pacjentem.</p>
        </div>
        <div class="mission-box">
          <h3>Empatia</h3>
          <p>Stawiamy pacjenta w centrum uwagi – jego potrzeby są naszym priorytetem.</p>
        </div>
        <div class="mission-box">
          <h3>Nowoczesność</h3>
          <p>Inwestujemy w innowacyjne technologie, by świadczyć usługi na najwyższym poziomie.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section highlightt">
    <div class="container columns">
      <div class="col highlight-text">
        <h2>Dlaczego warto nam zaufać?</h2>
        <ul class="icon-list">
          <li>Empatyczne podejście do każdego pacjenta</li>
          <li>Wieloletnie doświadczenie i certyfikowani specjaliści</li>
          <li>Bezpieczeństwo i komfort w nowoczesnych wnętrzach</li>
          <li>Elastyczne godziny przyjęć i szybka rejestracja</li>
        </ul>
      </div>
      <div class="col highlight-image">
        <img src="image/gabinet.jpg" alt="MediClinic - wnętrze kliniki" />
      </div>
    </div>
  </section>

  <section class="cta full-width">
    <div class="container cta-content">
      <h2>Umów wizytę już dziś</h2>
      <a href="kontakt.php" class="btnn">Skontaktuj się z nami</a>
    </div>
  </section>

  <section class="newsletter">
    <div class="container newsletter-content">
      <h2>Zapisz się do newslettera</h2>
      <p>Bądź na bieżąco z nowościami i wydarzeniami w MediClinic.</p>
      <form>
        <input type="email" placeholder="Twój adres e-mail" />
        <button type="submit">Zapisz się</button>
      </form>
    </div>
  </section>

  <?php include 'includes/footer.php'; ?>



  <script src="script-fixed.js"></script>

  <button id="scrollToTop" title="Wróć na górę">↑</button>
</body>
</html>
