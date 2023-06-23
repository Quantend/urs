<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Verbinding maken met de database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "erp";
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Factuurgegevens ophalen op basis van de geselecteerde opdracht
  if (isset($_POST['opdrachtId'])) {
    $opdrachtId = $_POST['opdrachtId'];

    // Query om de factuurgegevens op te halen
    $query = "SELECT klanten.Adres, klanten.Bedrijfsnaam, klanten.Voornaam, klanten.Tussenvoegsel, klanten.Achternaam, klanten.Telefoonnummer, klanten.Email, opdrachten.Datum_van_aanvraag, opdrachten.Omschrijving, werkzaamheden.Datum, werkzaamheden.`Type Werkzaamheden`, werkzaamheden.`Gewerkte Uren`, medewerkers.Voornaam AS MedewerkerVoornaam, medewerkers.Tussenvoegsel AS MedewerkerTussenvoegsel, medewerkers.Achternaam AS MedewerkerAchternaam
    FROM opdrachten
    INNER JOIN klanten ON opdrachten.Klanten_ID = klanten.Klanten_ID
    INNER JOIN werkzaamheden ON opdrachten.Opdracht = werkzaamheden.Opdracht
    INNER JOIN medewerkers ON werkzaamheden.Medewerkers_ID = medewerkers.Medewerkers_ID
    WHERE opdrachten.Opdracht = '$opdrachtId'";

    $result = $conn->query($query);

    // Factuurgegevens weergeven
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      echo "<h2>Klantgegevens</h2>";
      echo "<p>Adres: " . $row['Adres'] . "</p>";
      echo "<p>Bedrijfsnaam: " . $row['Bedrijfsnaam'] . "</p>";
      echo "<p>Naam: " . $row['Voornaam'] . " " . $row['Tussenvoegsel'] . " " . $row['Achternaam'] . "</p>";
      echo "<p>Telefoonnummer: " . $row['Telefoonnummer'] . "</p>";
      echo "<p>E-mail: " . $row['Email'] . "</p>";

      echo "<h2>Opdrachtgegevens</h2>";
      echo "<p>Datum van aanvraag: " . $row['Datum_van_aanvraag'] . "</p>";
      echo "<p>Omschrijving: " . $row['Omschrijving'] . "</p>";

      echo "<h2>Werkzaamheden</h2>";
      echo "<table>";
      echo "<tr><th>Datum</th><th>Type Werkzaamheden</th><th>Gewerkte Uren</th><th>Medewerker</th></tr>";

      // Lus om door alle rijen met werkzaamheden te itereren
      $totaalUren = 0; // Variabele voor het totaal aantal uren
      $totaalMinuten = 0; // Variabele voor het totaal aantal minuten
      while ($row = $result->fetch_assoc()) {
        $gewerkteUren = $row['Gewerkte Uren'];
        list($uren, $minuten) = explode(':', $gewerkteUren); // De gewerkte uren en minuten splitsen
        $totaalUren += (int)$uren; // Het aantal gewerkte uren bij het totaal optellen
        $totaalMinuten += (int)$minuten; // Het aantal gewerkte minuten bij het totaal optellen

        echo "<tr>";
        echo "<td>" . $row['Datum'] . "</td>";
        echo "<td>" . $row['Type Werkzaamheden'] . "</td>";
        echo "<td>" . $gewerkteUren . "</td>";
        echo "<td>" . $row['MedewerkerVoornaam'] . " " . $row['MedewerkerTussenvoegsel'] . " " . $row['MedewerkerAchternaam'] . "</td>";
        echo "</tr>";
      }

      $totaalUren += floor($totaalMinuten / 60); // Het aantal uren aanpassen op basis van de totale minuten
      $totaalMinuten = $totaalMinuten % 60; // Het aantal minuten aanpassen op basis van de resterende minuten

      echo "</table>";

      echo "<p>Totaal aantal uren: " . $totaalUren . ":" . $totaalMinuten . "</p>"; // Totaal aantal uren en minuten weergeven
    }
  }

  // Databaseverbinding sluiten
  $conn->close();
}
?>
