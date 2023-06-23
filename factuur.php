<?php
session_start();
// Controleren of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Gebruiker is niet ingelogd, doorsturen naar inlogpagina
    header("location: login.php");
    exit;
}

// Controleren of de gebruiker de juiste rol heeft
if ($_SESSION['role'] !== 'admin') {
    // Onbevoegde toegang, doorsturen naar home pagina
    header("location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factuur</title>
    <link rel="stylesheet" type="text/css" href="factuur.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="favicon-factuur.ico">
    <script src="factuur.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/13f83daf59.js" crossorigin="anonymous"></script>
    <script src="menu-script.js"></script>
</head>

<body>

<nav>
        <div class="logo">Urenregistratie</div>
        <input type="checkbox" id="click">
        <label for="click" class="menu-btn"><i class="fas fa-bars"></i></label>
        <ul>
            <li><a href="admin.php">Home</a></li>
            <li><a href="medewerkers.php">Medewerkers</a></li>
            <li><a href="opdrachten.php">Opdrachten</a></li>
            <li><a href="werkzaamheden.php">Werkzaamheden</a></li>
            <li><a href="klanten.php">Klanten</a></li>
            <li><a href="registratie_admin.php">Uren Registreren</a></li>
            <li><a class="active" href="factuur.php">Factuur</a></li>
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-door-open"></i></a></li>
        </ul>
    </nav>

  <h1>Factuur</h1>

  <div id="factuurWrapper">
  <form id="factuurForm">
    <label for="opdrachtSelect">opdracht:</label>
    <select id="opdrachtSelect">
      <?php
        // Verbinding maken met de database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "erp";
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Opdrachten ophalen uit de database
        $opdrachtenQuery = "SELECT * FROM opdrachten";
        $opdrachtenResult = $conn->query($opdrachtenQuery);

        // Opties weergeven in de selectbox
        while ($row = $opdrachtenResult->fetch_assoc()) {
          $opdrachtId = $row['Opdracht'];
          $opdrachtOmschrijving = $row['Omschrijving'];
          echo "<option value='$opdrachtId'>$opdrachtId</option>";
        }

        // Databaseverbinding sluiten
        $conn->close();
      ?>
    </select>
    <button type="submit">Genereer factuur</button>
  </form>
  </div>
  
  <div>   <button id="download" type="button">Download PDF</button> </div>
  <div id="factuurDetails"></div>

</body>
</html>