<?php
session_start();
// Controleren of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Gebruiker is niet ingelogd, doorsturen naar inlogpagina
    header("location: login.php");
    exit;
}

// Controleren of de gebruiker de juiste rol heeft
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'medewerker') {
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

// Haal de ingelogde medewerkers_id op
$Medewerkers_ID = $_SESSION['Medewerkers_ID'];

// Controleren of het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valideren van de ingediende gegevens
    $Medewerkers_ID = $_POST["Medewerkers_ID"];
    $Datum = $_POST["datum"];
    $Opdracht = $_POST["opdracht"];
    $Type_werkzaamheden = $_POST["type_werkzaamheden"];
    $Start_tijd = $_POST["start_tijd"];
    $Eind_tijd = $_POST["eind_tijd"];
    $Pauze_minuten = isset($_POST["pauze_minuten"]) ? $_POST["pauze_minuten"] : 0; 
    $Gewerkte_uren = $_POST["gewerkte_uren"];
    

    // Controleren of de ingevulde gegevens geldig zijn
    if (empty($Medewerkers_ID) || empty($Datum) || empty($Type_werkzaamheden) || empty($Start_tijd) || empty($Eind_tijd) || empty($Gewerkte_uren) || empty($Opdracht)) {
        $messageClass = "validation-error";
        $message = "Vul alle velden in.";
    } else {
        // Query om de gegevens in de database in te voegen
        $sql = "INSERT INTO werkzaamheden (Medewerkers_ID, Datum, `Type Werkzaamheden`, `Start tijd`, `Eind tijd`, `Pauze tijd`, `gewerkte uren`, Opdracht) VALUES ('$Medewerkers_ID', '$Datum', '$Type_werkzaamheden', '$Start_tijd', '$Eind_tijd', ";

        // Controleren of pauzetijd is ingevuld
        if (!empty($Pauze_minuten)) {
            $sql .= "TIME_FORMAT(SEC_TO_TIME($Pauze_minuten * 60), '%H:%i'), ";
        } else {
            $sql .= "0, ";
        }

        $sql .= "'$Gewerkte_uren', '$Opdracht')";

        // Uitvoeren van de query
        if ($conn->query($sql) === TRUE) {
            $messageClass = "success-message";
            $message = "Werkzaamheden succesvol opgeslagen!";
            header("refresh:4;url=registratie.php");
        } else {
            $messageClass = "error-message";
            $message = "Er is een fout opgetreden: " . $conn->error;
        }
    }

    
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
    <link rel="stylesheet" type="text/css" href="form_style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
    <script src="https://kit.fontawesome.com/13f83daf59.js" crossorigin="anonymous"></script>
    <script src="menu-script.js"></script>
</head>

<body>
    <nav>
        <div class="logo">Urenregistratie</div>
        <input type="checkbox" id="click">
        <label for="click" class="menu-btn"><i class="fas fa-bars"></i></label>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a class="active" href="registratie.php">Uren Registreren</a></li>
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-door-open"></i></a></li>
        </ul>
    </nav>

    <header>
        <h1>Uren Registreren</h1>
    </header>

    <?php if (isset($message)): ?>
        <div class="notification-container">
            <div id="message" class="<?php echo $messageClass; ?>"><?php echo $message; ?></div>
        </div>
    <?php endif; ?>

    <main id="main_reg">
        <form method="post" id="registratie-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Medewerkers ID:<input min="1" value="<?php echo $Medewerkers_ID; ?>" type="number" name="Medewerkers_ID" readonly><br>
            Datum: <input type="date" name="datum" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required><br>
            Type Werkzaamheden: <textarea placeholder="Wat heb je gedaan?" name="type_werkzaamheden"></textarea><br>
            Opdracht:
            <select name="opdracht">
                <?php
                // Query om de opdrachten op te halen uit de database
                $opdrachtQuery = "SELECT DISTINCT Opdracht FROM opdrachten";
                $opdrachtResult = mysqli_query($conn, $opdrachtQuery);
                while ($row = mysqli_fetch_assoc($opdrachtResult)) {
                    echo "<option value='" . $row['Opdracht'] . "'>" . $row['Opdracht'] . "</option>";
                }
                ?>
            </select><br>
            Start tijd: <input type="time" name="start_tijd" id="start_tijd"><br>
            Eind tijd: <input type="time" name="eind_tijd" id="eind_tijd"><br>
            Pauze duur (minuten): <input type="number" name="pauze_minuten" id="pauze_minuten" min="0"><br>
            Gewerkte uren: <input type="text" name="gewerkte_uren" id="gewerkte_uren" readonly><br>
            <input type="submit" value="Werkzaamheden opslaan" id="reg_button">
        </form>
    </main>

    <footer class="page-footer">
        <p1><a href="https://www.gildeopleidingen.nl/">&copy; 2023 Groep 6 | Gilde Devops</a></p1>
    </footer>

    <script src="form-script.js"></script>
</body>

</html>

<?php
// Databaseverbinding sluiten
    $conn->close();
?>