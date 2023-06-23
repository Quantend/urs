<?php
session_start();
// Controleren of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== true) {
    // Gebruiker is niet ingelogd, doorsturen naar inlogpagina
    header("location: login.php");
    exit;
}

// Controleren of de gebruiker de juiste rol heeft
if ($_SESSION['role'] !== 'medewerker') {
    // Admin doorsturen naar admin pagina
    header("location: admin.php");
    exit;
}

// Verbinding maken met de database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "erp";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Voornaam van de ingelogde medewerker ophalen
$Medewerkers_ID = $_SESSION['Medewerkers_ID'];
$sql = "SELECT Voornaam FROM Medewerkers WHERE Medewerkers_ID = $Medewerkers_ID";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $Voornaam = $row['Voornaam'];
} else {
    $Voornaam = "Onbekend";
}

// Tijdstip van de dag bepalen
$currentTime = date("H:i:s");
$greeting = "";
if ($currentTime < "12:00:00") {
    $greeting = "Goedemorgen";
} elseif ($currentTime < "18:00:00") {
    $greeting = "Goedemiddag";
} else {
    $greeting = "Goedenavond";
}

// Voer de query uit om de gegevens op te halen en de tabellen te koppelen
$sql = "SELECT werkzaamheden.*, CONCAT(medewerkers.Voornaam, ' ', medewerkers.Tussenvoegsel, ' ', medewerkers.Achternaam) AS Naam FROM werkzaamheden JOIN medewerkers ON werkzaamheden.Medewerkers_ID = medewerkers.Medewerkers_ID WHERE werkzaamheden.Medewerkers_ID = $Medewerkers_ID";
$result = mysqli_query($conn, $sql);

// Databaseverbinding sluiten
mysqli_close($conn);
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
            <li><a href="registratie.php">Uren registreren</a></li>
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-door-open"></i></a></li>
        </ul>
    </nav>

<header>
    <h1><?php echo $greeting . ' ' . $Voornaam; ?></h1>
</header>

<main id="main_tabel">
<h2>Je werkzaamheden</h2>
    <section>
        <div class="table-container">
        <table>
            <tr>
                <th>Datum</th>
                <th>Type Werkzaamheden</th>
                <th>Start tijd</th>
                <th>Eind tijd</th>
                <th>Pauze tijd</th>
                <th>Gewerkte uren</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["Datum"] . "</td>";
                echo "<td>" . $row["Type Werkzaamheden"] . "</td>";
                echo "<td>" . $row["Start tijd"] . "</td>";
                echo "<td>" . $row["Eind tijd"] . "</td>";
                echo "<td>" . $row["Pauze tijd"] . "</td>";
                echo "<td>" . $row["Gewerkte uren"] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        </div>
    </section>
</main>

<footer class="page-footer">
    <p1><a href="https://www.gildeopleidingen.nl/">&copy; 2023 Groep 6 | Gilde Devops</a></p1> 
</footer>

<script src="table-scroll.js"></script>

</body>
</html>