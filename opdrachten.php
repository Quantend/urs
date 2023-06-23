<?php
session_start();
// Controleren of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== true) {
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

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "erp";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urenregistratie</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="tabel_style.css">
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
            <li><a class="active" href="opdrachten.php">Opdrachten</a></li>
            <li><a href="werkzaamheden.php">Werkzaamheden</a></li>
            <li><a href="klanten.php">Klanten</a></li>
			      <li><a href="registratie_admin.php">Uren registreren</a></li>
            <li><a href="factuur.php">Factuur</a></li>
			      <li><a href="logout.php" class="logout-btn"><i class="fas fa-door-open"></i></a></li>
        </ul>
    </nav>

<header>
	<h1>Tabel opdrachten</h1>
</header>

<input type="text" id="search" placeholder="Zoeken...">

<main id="main_tabel">
	<section>
		<?php
			$sql = "SELECT * FROM opdrachten";
			$result = mysqli_query($conn, $sql);
		?>
		<div class="table-container">
		<table id="opdrachtenTable">
			<tr>
				<th>Opdracht</th>
				<th>Klant ID</th>
				<th>Titel</th>
				<th>Omschrijving</th>
				<th>Datum van aanvraag</th>
				<th>Benodigde kennis</th>
				<th>Acties</th>
			</tr>
			<?php
				while($row = mysqli_fetch_assoc($result)) {
					echo "<tr><td>".$row["Opdracht"]."</td><td>".$row["Klanten_ID"]."</td><td>".$row["Titel"]."</td><td>".$row["Omschrijving"]."</td><td>".$row["Datum_van_aanvraag"]."</td><td>".$row["Benodigde kennis"]."</td><td>";
				}
			?>
		</table>
		</div>
	</section>
</main>

<footer class="page-footer">
	<p1><a href="https://www.gildeopleidingen.nl/">&copy; 2023 Groep 6 | Gilde Devops</a></p1> 
</footer>

<script>
  function filterTable() {
  // Haal de zoekterm op uit het zoekvak
  var searchInput = document.getElementById('search').value.toLowerCase();

  // Selecteer de tabel en de rijen
  var table = document.getElementById('opdrachtenTable');
  var rows = table.getElementsByTagName('tr');

  // Loop door alle rijen, beginnend bij index 1 om de header over te slaan
  for (var i = 1; i < rows.length; i++) {
    var row = rows[i];
    var rowData = row.getElementsByTagName('td');

    var found = false;

    // Loop door de eerste 2 celgegevens in de rij
    for (var j = 0; j < 2; j++) {
      var cellData = rowData[j].innerText.toLowerCase();

      // Controleer of de celgegevens overeenkomen met de zoekterm
      if (cellData.indexOf(searchInput) > -1) {
        found = true;
        break;
      }
    }

    // Toon of verberg de rij op basis van de zoekresultaten
    if (found) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  }
}

// Voeg een event listener toe aan het zoekvak om de tabel te filteren bij elke wijziging
document.getElementById('search').addEventListener('input', filterTable);
</script>

<script src="table-scroll.js"></script>

</body>
</html>