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
  <script type="text/javascript">
    var HjemAdresse = "http://www.sarlog.net/";
    var APIServer = document.URL;
    var LoggID = 0;
    var LoggtypeID = 0;
    var Kallesignal = 0;
    var LogglinjeTidsstempel = 0;
    var LagslisteTidsstempel = 0;
    var TimerLogglisteID = 0;
    var TimerLagslisteID = 0;
    var OpprettLagAutomatisk = 1;

    function OppdaterKlokke() {
      var ManedNavn = ["jan", "feb", "mar", "apr", "mai", "jun", "jul", "aug", "sep", "okt", "nov", "des"];
      var DagNavn = ["S&#248;ndag", "Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "L&#248;rdag"];

      var currentTime = new Date();
      var currentHours = currentTime.getHours();
      var currentMinutes = currentTime.getMinutes();
      var currentSeconds = currentTime.getSeconds();

      tidTime = (currentHours < 10 ? "0" : "") + currentHours;
      tidMinutter = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
      tidSekunder = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

      $("#KlokkeTid").html(tidTime+":"+tidMinutter+":"+tidSekunder);
      $("#KlokkeDato").html(DagNavn[currentTime.getDay()] + " " + currentTime.getDate() + " " + ManedNavn[currentTime.getMonth()] + " " + currentTime.getFullYear());
      $("#SkjemaDTG").attr('placeholder',(currentTime.getDate()+tidTime+tidMinutter));
    }
	
    function ResetVindu() {
      LoggID = 0;
      LoggtypeID = 0;
      LogglinjeTidsstempel = 0;
      LagslisteTidsstempel = 0;
      document.title = "SARLog3";
      ResetListeTopp();
      ResetLoggliste();
      clearInterval(TimerLogglisteID);
      clearInterval(TimerLagslisteID);
      $("#SideLagsliste").hide();
      $("#SideLagsliste").empty();
      $("#Bunnskjema").hide();
      $("#Bunnskjema").empty();
    }
    
    function ResetListeTopp() {
      $("#ListeTopp").empty();
    }
    
    function ResetLoggliste() {
      $("#ListeLogglinjer").empty();
      content = "  <tr class=\"Normal\">";
      content += "    <td colspan=\"5\" class=\"KolonneMelding\"><center><i>---- Ingen logglinjer å vise ----</i></center></td>";
      content += "  </tr>";
      $(content).appendTo("#ListeLogglinjer");
    }

    function ResetSkjema() {
      if (LoggtypeID == 0) {
        $("#SkjemaDTG").val("");
        $("#SkjemaMelding").val("");
        $("#SkjemaMelding").focus();
      } else if (LoggtypeID == 1) {
        $("#MeldingSlutt").val("0");
        $("#SkjemaFra").css("background-color","#fff");
        $("#SkjemaTil").css("background-color","#fff");
        $("#SkjemaDTG").val("");
        $("#SkjemaFra").val("");
        $("#SkjemaTil").val("");
        $("#SkjemaMelding").val("");
        $("#SkjemaTil").focus();
      } else if (LoggtypeID == 2) {
        $("#SkjemaDTG").val("");
        $("#SkjemaMelding").val("");
        $("#SkjemaMelding").focus();
      }
    }

    function ResetSkjemaOver() {
      $("#MeldingSlutt").val("0");
      $("#SkjemaFra").css("background-color","#fff");
      $("#SkjemaTil").css("background-color","#fff");
      $("#SkjemaDTG").val("");
      var temp = $("#SkjemaFra").val();
      $("#SkjemaFra").val($("#SkjemaTil").val());
      $("#SkjemaTil").val(temp);
      $("#SkjemaMelding").val("");
      $("#SkjemaMelding").focus();
    }

    function HentLoggInfo() {
      if (LoggID > 0) {
        $.getJSON(APIServer+"api.php/logg/loggdata?loggid="+LoggID+"&uts="+new Date().getTime(), function(data) {
          if (data != null) {
            LoggtypeID = data.LoggtypeID;
            Kallesignal = data.Kallesignal;
            document.title = "SARLog: "+data.Navn;
            OppsettListeTopp();
            OppdaterLoggliste();
            TimerLogglisteID = setInterval("OppdaterLoggliste()", 20000);
            OppsettBunnskjema();
            $("#Bunnskjema").show();
            if (AktivitetID > 0) {
              OppdaterLagsliste();
              TimerLagslisteID = setInterval("OppdaterLagsliste()", 60000);
              $("#SideLagsliste").show();
            }
          }
        });
      }
    }

    function OppsettListeTopp() {
      $("#ListeTopp").empty();
      content = "";
      if (LoggtypeID == 0) {
        content += "<span class=\"KolonneNr\">NR</span>";
        content += "<span class=\"KolonneDTG\">DTG</span>";
        content += "<span class=\"KolonneMelding\">MELDING</span>";
      } else if (LoggtypeID == 1) {
        content += "<span class=\"KolonneNr\">NR</span>";
        content += "<span class=\"KolonneDTG\">DTG</span>";
        content += "<span class=\"KolonneTil\">TIL</span>";
        content += "<span class=\"KolonneFra\">FRA</span>";
        content += "<span class=\"KolonneMelding\">MELDING</span>";
      } else if (LoggtypeID == 2) {
        content += "<span class=\"KolonneNr\">NR</span>";
        content += "<span class=\"KolonneDTG\">DTG</span>";
        content += "<span class=\"KolonneMelding\">SKADE OG BEHANDLING</span>";
      }
      $(content).appendTo("#ListeTopp");
    }
    
    function OppsettBunnskjema() {
      $("#Bunnskjema").empty();
      content = "";
      if (LoggtypeID == 0) {
        content += "<form action=\"index.php\" method=\"POST\" id=\"SkjemaAL\">";
        content += "<table id=\"Skjema\" width=\"100%\" height=\"100%\">";
        content += "  <tr>";
        content += "    <td width=\"10%\"><input type=\"text\" name=\"SkjemaDTG\" id=\"SkjemaDTG\" size=\"8\" maxlength=\"6\" placeholder=\"DTG\" /></td>";
        content += "    <td><textarea name=\"SkjemaMelding\" id=\"SkjemaMelding\" placeholder=\"Melding\" style=\"height: 70px;width:95%;\"></textarea></td>";
        content += "    <td width=\"15%\"><button id=\"KnappLagreSkjema\">Lagre</button></td>";
        content += "  </tr>";
        content += "</table>";
        content += "</form>";
        $(content).appendTo("#Bunnskjema");
        OppsettSkjemaAL();
      } else if (LoggtypeID == 1) {
        content += "<form action=\"index.php\" method=\"POST\" id=\"SkjemaSL\">";
        content += "<input type=\"hidden\" name=\"MeldingSlutt\" id=\"MeldingSlutt\" />";
        content += "<table id=\"Skjema\" width=\"100%\" height=\"100%\">";
        content += "  <tr>";
        content += "    <td width=\"10%\"><input type=\"text\" name=\"SkjemaDTG\" id=\"SkjemaDTG\" size=\"8\" maxlength=\"6\" placeholder=\"DTG\" /></td>";
        content += "    <td width=\"9%\"><input type=\"text\" name=\"SkjemaTil\" id=\"SkjemaTil\" size=\"6\" maxlength=\"3\" placeholder=\"Til xx\" /></td>";
        content += "    <td width=\"9%\"><input type=\"text\" name=\"SkjemaFra\" id=\"SkjemaFra\" size=\"6\" maxlength=\"3\" placeholder=\"Fra xx\" /></td>";
        content += "    <td><textarea name=\"SkjemaMelding\" id=\"SkjemaMelding\" placeholder=\"Melding\" style=\"height: 70px;width:95%;\"></textarea></td>";
        content += "    <td width=\"15%\"><button id=\"KnappLagreSkjemaOver\">Over</button>&nbsp;<button id=\"KnappLagreSkjemaSlutt\">Slutt</button></td>";
        content += "  </tr>";
        content += "</table>";
        content += "</form>";
        $(content).appendTo("#Bunnskjema");
        OppsettSkjemaSL();
      } else if (LoggtypeID == 2) {
        content += "<form action=\"index.php\" method=\"POST\" id=\"SkjemaSAN\">";
        content += "<table id=\"Skjema\" width=\"100%\" height=\"100%\">";
        content += "  <tr>";
        content += "    <td width=\"10%\"><input type=\"text\" name=\"SkjemaDTG\" id=\"SkjemaDTG\" size=\"8\" maxlength=\"6\" placeholder=\"DTG\" /></td>";
        content += "    <td><textarea name=\"SkjemaMelding\" id=\"SkjemaMelding\" placeholder=\"Melding\" style=\"height: 70px;width:95%;\"></textarea></td>";
        content += "    <td width=\"15%\"><button id=\"KnappLagreSkjema\">Lagre</button></td>";
        content += "  </tr>";
        content += "</table>";
        content += "</form>";
        $(content).appendTo("#Bunnskjema");
        OppsettSkjemaSAN();
      }
    }
    
    function OppsettSkjemaAL() {
      $("#SkjemaAL").submit(function(event) {
        event.preventDefault();

        var FormStatus = 1;

        if ($("#SkjemaMelding").val().length == 0) {
          alert("Logglinjen må inneholde en melding!");
          FormStatus = 0;
        }

        if (FormStatus == 1) {
          var NyLinje = new Object();
          if ($("#SkjemaDTG").val() == "") {
            NyLinje.DTG = $("#SkjemaDTG").attr('placeholder');
          } else {
            NyLinje.DTG = $("#SkjemaDTG").val();
          }
          NyLinje.Melding = $("#SkjemaMelding").val();
          var jqxhr = $.post(APIServer+"api.php/logg/lagrelinje?loggid="+LoggID, NyLinje )
            .success(function() {
              ResetSkjema();
              OppdaterLoggliste();
            })
            .error(function(data) {
              alert("En feil oppstod!"+data.responseText);
            })
          delete NyLinje;
        }
      });
    }
    
    function OppsettSkjemaSL() {
      $("#SkjemaSL").submit(function(event) {
        event.preventDefault();

        var FormStatus = 1;

        if (($("#SkjemaFra").val().length == 0) && ($("#SkjemaTil").val().length == 0)) {
          alert("Feltene til og fra kan ikke begge v&#230;re tomme!");
          FormStatus = 0;
        } else {
          if ($("#SkjemaFra").val().length == 0) {
            $("#SkjemaFra").val(Kallesignal);
          }
          if ($("#SkjemaTil").val().length == 0) {
            $("#SkjemaTil").val(Kallesignal);
          }
        }
        if ($("#SkjemaMelding").val().length == 0) {
          alert("Logglinjen m&#229; inneholde en melding!");
          FormStatus = 0;
        }

        if (FormStatus == 1) {
          var NyLinje = new Object();
          NyLinje.OpprettLagAutomatisk = OpprettLagAutomatisk;
          if ($("#SkjemaDTG").val() == "") {
            NyLinje.DTG = $("#SkjemaDTG").attr('placeholder');
          } else {
            NyLinje.DTG = $("#SkjemaDTG").val();
          }
          NyLinje.Fra = $("#SkjemaFra").val();
          NyLinje.Til = $("#SkjemaTil").val();
          NyLinje.Melding = $("#SkjemaMelding").val();
          NyLinje.Slutt = $("#MeldingSlutt").val();
          var jqxhr = $.post(APIServer+"api.php/logg/lagrelinje?loggid="+LoggID, NyLinje )
            .success(function() {
              if ($("#MeldingSlutt").val() == 1) {
                ResetSkjema();
              } else {
                ResetSkjemaOver();
              }
              OppdaterLoggliste();
              OppdaterLagsliste();
            })
            .error(function(data) {
              alert("En feil oppstod!"+data.responseText);
            })
          delete NyLinje;
        }
      });
      $("#KnappLagreSkjemaOver").click(function() {
        $("#MeldingSlutt").val("0");
      });
      $("#KnappLagreSkjemaSlutt").click(function() {
        $("#MeldingSlutt").val("1");
      });
    }

    function OppsettSkjemaSAN() {
      $("#SkjemaSAN").submit(function(event) {
        event.preventDefault();

        var FormStatus = 1;

        if ($("#SkjemaMelding").val().length == 0) {
          alert("Logglinjen må inneholde en melding!");
          FormStatus = 0;
        }

        if (FormStatus == 1) {
          var NyLinje = new Object();
          if ($("#SkjemaDTG").val() == "") {
            NyLinje.DTG = $("#SkjemaDTG").attr('placeholder');
          } else {
            NyLinje.DTG = $("#SkjemaDTG").val();
          }
          NyLinje.Melding = $("#SkjemaMelding").val();
          var jqxhr = $.post(APIServer+"api.php/logg/lagrelinje?loggid="+LoggID, NyLinje )
            .success(function() {
              ResetSkjema();
              OppdaterLoggliste();
            })
            .error(function(data) {
              alert("En feil oppstod!"+data.responseText);
            })
          delete NyLinje;
        }
      });
    }

    function OppdaterLoggliste() {
      if (LoggID > 0) {
        $.getJSON(APIServer+"api.php/logg/linjer?loggid="+LoggID+"&uts=" + new Date().getTime(), function(data) {
          if (data.Linjer != null) {
            //if (data.DatoSisteMelding > LogglinjeTidsstempel) {
              $("#ListeLogglinjer").empty();

              $.each(data.Linjer, function(i,linje) {
                content = "";
                if (linje.Nr % 2 == 0) {
                  content += "  <tr class=\"Odd\">";
                } else {
                  content += "  <tr>";
                }
                content += "    <td class=\"KolonneNr\">" + linje.Nr + "</td>";
                content += "    <td class=\"KolonneDTG\">" + linje.DTG + "</td>";
                if (LoggtypeID == 1) {
                  if (linje.Til == Kallesignal) {
                    content += "    <td class=\"KolonneTil\">" + linje.Til + "</td>";
                  } else {
                    content += "    <td class=\"KolonneTil\"><b>" + linje.Til + "</b></td>";
                  }
                  if (linje.Fra == Kallesignal) {
                    content += "    <td class=\"KolonneFra\">" + linje.Fra + "</td>";
                  } else {
                    content += "    <td class=\"KolonneFra\"><b>" + linje.Fra + "</b></td>";
                  }
                }
                content += "    <td class=\"KolonneMelding\" >" + linje.Melding + "</td>";
                content += "  </tr>";
                $(content).appendTo("#ListeLogglinjer");
                $('#Sideinnhold').scrollTop($('#Sideinnhold')[0].scrollHeight);
              });
              LogglinjeTidsstempel = data.DatoSisteMelding;
              $(".LogglinjeNy").effect("pulsate");
            //}
          } else {
            if (data.Linjer == 0) {
              ResetLoggliste();
            }
          }
        });
      }
    }

    function OppdaterLagsliste() {
      if (AktivitetID > 0) {
        $.getJSON(APIServer+"api.php/mannskap/lagsliste?aktivitetid="+AktivitetID+"&uts=" + new Date().getTime(), function(data) {
        if (data.Lagsliste != null) {
          if (data.SistEndret > LagslisteTidsstempel) {
            $("#SideLagsliste").empty();
            content = "";

            $.each(data.Lagsliste, function(i, lag) {
              if (lag.Kallesignal.length > 0) {
                content += "<div class=\"LagsInfo\">";
                content += "<table class=\"LagstatusUkjent\">";
                content += "  <tr>";
                content += "    <td class=\"Kallesignal\">"+lag.Kallesignal+"</td>";
                content += "    <td class=\"Lagsnavn\">"+lag.Navn+"</td>";
                if (lag.SisteMeldingMinutter > 30) {
                  content += "    <td class=\"Tid\"><img src=\"/grafikk/clock_icon.png\" height=\"10\">&nbsp;"+lag.SisteMeldingMinutter+"</td>";
                } else {
                  content += "    <td class=\"Tid\">&nbsp;</td>";
                }
                content += "  </tr>";
                content += "</table>";
                content += "</div>";
              }
            });

            $(content).appendTo("#SideLagsliste");
            LagslisteTidsstempel = data.SistEndret;
          }
        }
      });
      }
    }

    function LoggApne(NyLoggID) {
      ResetVindu();
      LoggID = NyLoggID;
      HentLoggInfo();
    }

    $(document).ready(function() {
      OppdaterKlokke();
      setInterval("OppdaterKlokke()", 1000);
      ResetVindu();
    });

  </script>
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
<button id="MenyHjem">Hjem</button>
<button id="MenyNy">Ny</button>
<button id="MenyApne">Åpne</button>
<button id="MenyOppdater">Oppdater</button>
<button id="MenyLukk">Lukk</button>
</div>
</div>

<script>
  $("#MenyHjem").button({
    text: false,
    icons: {
      primary: "ui-icon-home"
    },
  });
  
  $("#MenyHjem").click(function() {
    location.href = HjemAdresse;
  });
  
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
      $.get(APIServer+"api.php/logg/logger/", function (data) {
        logger = JSON.parse(data);
        //alert(data);
        //if (logger.length > 0) {
          $.each(logger, function(i, logg) {
            $("#LoggerListe").append($("<option value=\""+logg.ID+"\">"+logg.Navn+"</option>"));
          });
        //} else {
        //  alert("Fant ingen logger i systemet. Lag ny.");
        //}
      });
    },
    close: function() {
      $("#LoggerListe").empty();
    }
  });
</script>

<div id="DialogNyLogg">
<p>Logger som ikke blir knyttet opp til en aktivitet vil mangle funksjoner ut over vanlig logging da dette er funksjonalitet som lagres på aktiviteter, og ikke logger. Typen logg bestemmer hvilken type informasjon som lagres. Kallesignal er kun relevant til sambandslogger.</p><br />
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
    width: 500,
    modal: true,
    buttons: {
      "Opprett": function() {
        var NyLogg = new Object();
        NyLogg.LoggtypeID = $("#NyLoggLoggtypeID").val();
        NyLogg.Kallesignal = $("#NyLoggKallesignal").val();
        var jqxhr = $.post(APIServer+"api.php/logg/nylogg", NyLogg )
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
      $("#NyLoggKallesignal").val("");
    }
  });
</script>

</body>
</html>
