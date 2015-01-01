<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="pragma" content="no-cache" />
  <meta charset="utf-8" />
  <title>SARLog3</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/logg.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/smoothness/jquery-ui-1.10.3.custom.css" />
  <script src="<?php echo base_url(); ?>js/jquery-1.10.2.js"></script>
  <script src="<?php echo base_url(); ?>js/jquery-ui-1.10.3.custom.js"></script>
  <script src="<?php echo base_url(); ?>js/sarlog.js"></script>
</head>
<body>

<div id="ListeTopp">&nbsp;</div>

<div id="Sideinnhold">
<table width="100%" id="ListeLogglinjer"></table>
</div>


<div id="BunnskjemaPlaceholder">&nbsp;</div>
<div id="Bunnskjema">&nbsp;</div>


<div id="SideMeny">
<div class="Knapper">
<button id="MenyNy">Ny</button>
<button id="MenyApne">Åpne</button>
<button id="MenyOppdater">Oppdater</button>
<button id="MenyAvslutt">Arkiver</button>
<button id="MenyLastNed">Last ned</button>
<button id="MenyLukk">Lukk</button>
</div>
</div>

<script>
  $("#MenyNy").button({
    text: false,
    icons: {
      primary: "ui-icon-document"
    },
  });

  $("#MenyNy").click(function() {
    $("#DialogNyLogg").dialog("open");
  });

  $("#MenyApne").button({
    text: false,
    icons: {
      primary: "ui-icon-folder-open"
    },
  });

  $("#MenyApne").click(function() {
    $("#DialogApneLogg").dialog("open");
  });

  $("#MenyOppdater").button({
    text: false,
    icons: {
      primary: "ui-icon-refresh"
    },
  });
  
  $("#MenyOppdater").click(function() {
    LogglinjeTidsstempel = 0;
    LagslisteTidsstempel = 0;
    OppdaterLoggliste();
    OppdaterLagsliste();
  });

  $("#MenyAvslutt").button({
    text: false,
    icons: {
      primary: "ui-icon-locked"
    },
  });

  $("#MenyAvslutt").click(function() {
    $.get(APIServer+"api.php/logg/logg_avslutt?id="+LoggID);
    ResetVindu();
  });

  $("#MenyLastNed").button({
    text: false,
    icons: {
      primary: "ui-icon-disk"
    },
  });

  $("#MenyLastNed").click(function() {
    ResetVindu();
  });
  
  $("#MenyLukk").button({
    text: false,
    icons: {
      primary: "ui-icon-circle-close"
    },
  });
  
  $("#MenyLukk").click(function() {
    ResetVindu();
  });
</script>

<div id="SideKlokke"><span id="KlokkeTid">--</span><br /><span id="KlokkeDato">##</span></div>


<div id="SideLagslistePlaceholder">&nbsp;</div>
<div id="SideLagsliste">&nbsp;</div>


<div id="SideLogo"><img src="/grafikk/rkh-logo.jpg" width="200" /><br /></div>


<div id="DialogApneLogg">
<p>Velg loggen du ønsker å åpne.</p><br />
<select id="LoggerListe"></select>
</div>

<script>
  $("#DialogApneLogg").dialog({
    title: "Åpne logg",
    autoOpen: false,
    height: 350,
    width: 400,
    modal: true,
    buttons: {
      "Åpne": function() {
        LoggApne($("#LoggerListe").val());
        $(this).dialog("close");
      },
      "Lukk": function() {
        $(this).dialog("close");
      }
    },
    open: function() {
      $.get(APIServer+"api.php/logg/logg_liste/", function (data) {
        if (data !== 'null') { 
          logger = JSON.parse(data);
          $("#LoggerListe").append($("<optgroup label=\"Aktive logger\">"));
          $.each(logger, function(i, logg) {
            if (logg.DatoAvsluttet === "0000-00-00 00:00:00") {
              $("#LoggerListe").append($("<option value=\""+logg.ID+"\">"+logg.Type+": "+logg.Tittel+" ("+logg.Linjer+")</option>"));
            }
          });
          $("#LoggerListe").append($("</optgroup>"));
          $("#LoggerListe").append($("<optgroup label=\"Avsluttede logger\">"));
          $.each(logger, function(i, logg) {
            if (logg.DatoAvsluttet != "0000-00-00 00:00:00") {
              $("#LoggerListe").append($("<option value=\""+logg.ID+"\">"+logg.Type+": "+logg.Tittel+" ("+logg.Linjer+")</option>"));
            }
          });
          $("#LoggerListe").append($("</optgroup>"));
        } else {
          alert("Fant ingen logger i systemet!");
        }
      });
    },
    close: function() {
      $("#LoggerListe").empty();
    }
  });
</script>

<div id="DialogNyLogg">
<p>Typen logg bestemmer hvilken type informasjon som lagres. Kallesignal er kun relevant til sambandslogger.</p><br />
<table>
  <tr>
    <td>Type:</td>
    <td><select name="NyLoggLoggtypeID" id="NyLoggLoggtypeID">
      <option value="0">Aksjonslogg</option>
      <option value="1">Sambandslogg</option>
      <option value="2">Sanitetslogg</option>
    </select></td>
  </tr>
  <tr>
    <td>Tittel:</td>
    <td><input type="text" name="NyLoggTittel" id="NyLoggTittel" /></td>
  </tr>
  <tr>
    <td>Beskrivelse:</td>
    <td><input type="text" name="NyLoggBeskrivelse" id="NyLoggBeskrivelse" /></td>
  </tr>
  <tr>
    <td>Kallesignal:</td>
    <td><input type="text" name="NyLoggKallesignal" id="NyLoggKallesignal" /></td>
  </tr>
</table>
</div>

<script>
  $("#DialogNyLogg").dialog({
    title: "Opprett ny logg",
    autoOpen: false,
    height: 420,
    width: 540,
    modal: true,
    buttons: {
      "Lagre": function() {
        var NyLogg = new Object();
        NyLogg.TypeID = $("#NyLoggLoggtypeID").val();
        NyLogg.Tittel = $("#NyLoggTittel").val();
        NyLogg.Beskrivelse = $("#NyLoggBeskrivelse").val();
        NyLogg.Kallesignal = $("#NyLoggKallesignal").val();
        var jqxhr = $.post(APIServer+"api.php/logg/logg_opprett", NyLogg )
          .success(function(data) {
            LoggApne(data);
          })
          .error(function(data) {
            alert("En feil oppstod!"+data.responseText);
          })
        delete NyLogg;
        $(this).dialog("close");
      },
      "Lukk": function() {
        $(this).dialog("close");
      }
    },
    close: function() {
      $("#NyLoggLoggtypeID").val("");
      $("#NyLoggTittel").val("");
      $("#NyLoggBeskrivelse").val("");
      $("#NyLoggKallesignal").val("");
    }
  });
</script>

</body>
</html>
