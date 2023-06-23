$(document).ready(function() {
    // Functie om de factuurgegevens op te halen en weer te geven
    function toonFactuurDetails(opdrachtId) {
      $.ajax({
        url: 'factuurphp.php',
        type: 'POST',
        data: {opdrachtId: opdrachtId},
        success: function(response) {
          $("#factuurDetails").html(response);
        },
        error: function() {
          alert('Er is een fout opgetreden bij het ophalen van de factuurgegevens.');
        }
      });
    }
  
    // Eventlistener voor het formulier
    $('#factuurForm').submit(function(event) {
      event.preventDefault(); // Voorkomt het standaardgedrag van het formulier
      var opdrachtId = $('#opdrachtSelect').val();
      $("#factuurDetails").empty(); // Wis de oude factuurgegevens
      toonFactuurDetails(opdrachtId);
    });
  
    // Eventlistener voor de "Download PDF"-knop
    $('#download').click(function() {
      var opdrachtNaam = $('#opdrachtSelect option:selected').text();
      var datum = new Date().toISOString().slice(0, 10);
      var bestandsnaam = 'Factuur_' + opdrachtNaam + '_' + datum + '.pdf';
  
      const factuurDetails = document.getElementById('factuurDetails');
      html2pdf().from(factuurDetails).save(bestandsnaam);
    });
  });