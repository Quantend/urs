// JavaScript-code om de gewerkte uren te berekenen
function berekenGewerkteUren() {
  // Haal de waarden van de starttijd, eindtijd en pauzeminuten op
  var startTijd = document.getElementById("start_tijd").value;
  var eindTijd = document.getElementById("eind_tijd").value;
  var pauzeMinuten = document.getElementById("pauze_minuten").value;


  // Zet de tijdwaarden om naar milliseconden
  var startTijdMs = new Date("1970-01-01T" + startTijd + "Z").getTime();
  var eindTijdMs = new Date("1970-01-01T" + eindTijd + "Z").getTime();

  // Bereken het tijdsverschil tussen de starttijd en eindtijd
  var tijdsverschilMs = eindTijdMs - startTijdMs;

  // Haal de pauzetijd van het tijdsverschil af
  var gewerkteTijdMs = tijdsverschilMs - (pauzeMinuten * 60 * 1000);

  // Zet de gewerkte tijd om naar het formaat uur:minuut
  var gewerkteUren = new Date(gewerkteTijdMs).toISOString().substr(11, 5);

  // Vul de gewerkte tijd in het invoerveld in
  document.getElementById("gewerkte_uren").value = gewerkteUren;
}

// Voeg een event listener toe aan de eindtijd en pauzeminuten invoervelden om de gewerkte uren te berekenen
document.getElementById("eind_tijd").addEventListener("change", berekenGewerkteUren);
document.getElementById("pauze_minuten").addEventListener("change", berekenGewerkteUren);

  
// Functie om de melding te laten vervagen
 function fadeOutMessage() {
     var message = document.getElementById('message');
     if (message && message.textContent === 'Werkzaamheden succesvol opgeslagen!') {
       // Verwijder de melding na 3 seconden
       setTimeout(function() {
         // Voeg een CSS-klasse toe om de melding te laten vervagen
         message.classList.add('fade-out');
       }, 3000);

       // Verwijder de melding na 4 seconden
       setTimeout(function() {
         message.style.display = 'none';
       }, 4000);
     }
   }

   // Roep de functie aan wanneer de pagina is geladen
   window.addEventListener('DOMContentLoaded', fadeOutMessage);