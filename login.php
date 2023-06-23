<?php
session_start();

// Databasegegevens
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "erp";

// Verbinding maken met de database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Controleren of de databaseverbinding succesvol is
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($_SESSION['role'] === 'admin') {
        header("location: admin.php");
    } else {
        header("location: home.php");
    }
    exit;
}

// Controleren of het inlogformulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Medewerkers_ID = $_POST['Medewerkers_ID'];
    $password = $_POST['password'];

    // Query om gebruiker op te halen op basis van gebruikersnaam
    $query = "SELECT * FROM medewerkers WHERE Medewerkers_ID = '$Medewerkers_ID'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Controleren of het ingevoerde wachtwoord overeenkomt met het opgeslagen wachtwoord in de database
        if ($password == $row['password']) {
            // Inloggen is succesvol, sessievariabelen instellen
            $_SESSION['loggedin'] = true;
            $_SESSION['Medewerkers_ID'] = $row['Medewerkers_ID'];
            $_SESSION['role'] = $row['role'];

            // Doorsturen naar de juiste pagina op basis van de gebruikersrol
            if ($_SESSION['role'] === 'admin') {
                header("location: admin.php");
            } elseif ($_SESSION['role'] === 'medewerker') {
                header("location: home.php");
            } else {
                // Onbekende gebruikersrol, toon een foutmelding
                $loginError = "Onbekende gebruikersrol.";
            }
        } else {
            // Inloggen is mislukt, toon een foutmelding
            $loginError = "Ongeldige ID of wachtwoord.";
        }
    } else {
        // Gebruiker niet gevonden, toon een foutmelding
        $loginError = "Ongeldig ID of wachtwoord.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="login_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <script src="eye-script.js"></script>
</head>
<body>
    <div class="login-container">
        <h1>Inloggen</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="Medewerkers_ID"> Medewerkers ID:</label>
                <input type="text" id="Medewerkers_ID" name="Medewerkers_ID" required />
            </div>
            <div class="form-group password-toggle">
                <label for="password"> Wachtwoord:</label>
                <div class="input-with-icon">
                    <input type="password" id="password" name="password" required />
                    <span class="toggle-password">
                        <i class="bi bi-eye" id="togglePassword"></i>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" value="Inloggen" />
            </div>
            <?php if (isset($loginError)): ?>
                <div class="error"><?php echo $loginError; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>