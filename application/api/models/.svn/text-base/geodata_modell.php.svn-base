<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Geodata_modell extends CI_Model {
	
    // Returnerer ett array med søksområder knyttet til en aktivitet. AktivitetID er ett heltall. 
	// Hvis TidsStempel er angitt returneres alle objekter med en endring etter dette tidspunkt. 
	// Hvis ingen slike endringer finnes, så holdes responsen tilbake inntil den dukker opp
    function omrader($AktivitetID, $TidsStempel) {
	  $sql = $this->lagSelectSql("omrader", $AktivitetID, $TidsStempel);
	  
	  $data = "";
	  $omrader = $this->db->query($sql);
	  while (isset($TidsStempel) && $omrader->num_rows() == 0)
	  {
		sleep(1);
	    $omrader = $this->db->query($sql);
	  }
	  
	  foreach ($omrader->result() as $omrade)
		$data[] = $omrade;
	  
	  return $data;
    }

    // Lagrer ett søksområde i databasen, eller oppdaterer ett eksisterende. data er ett array med data.
    function lagreomrade($data) {
	  $tidsstempel = new DateTime();
	  if (!($data->ID))
	  {
		$data->ID = $this->finnNesteID("omrader");
		$handling = "I";
	  }
	  else
		$handling = "U";
	  
      $sql = 
"INSERT INTO omrader(ID, AktivitetID, TidsStempel, Handling, Navn, Polygon, OmradeType)
VALUES(" . $data->ID . ", " . $data->AktivitetID . ", '" . $tidsstempel->format('Y-m-d H:i:s.u') . "', '" . $handling . "', '" . $data->Navn . "', '" . $data->Polygon . "', '" . $data->OmradeType . "')";
	  $this->db->query($sql);
	  
	  $data->Handling = $handling;
	  $data->TidsStempel = $tidsstempel;
	  return $data;
    }

	// Sletter ett søksområde i databasen, ved å opprette en ny post merket D. data er et objekt.
    function slettomrade($data) {
	  $tidsstempel = new DateTime();
	  if ($data->ID)
	  {
		$handling = "D";
		$sql = 
"INSERT INTO omrader(ID, AktivitetID, TidsStempel, Handling, OmradeType)
VALUES(" . $data->ID . ", " . $data->AktivitetID . ", '" . $tidsstempel->format('Y-m-d H:i:s.u') . "', '" . $handling . "', '" . $data->OmradeType . "')";
		$this->db->query($sql);
	  }
	  
	  $data->TidsStempel = $tidsstempel;
	  $data->Handling = $handling;
	  return $data;
    }

    // Returnerer ett array med punkter knyttet til en aktivitet. AktivitetID er ett heltall.
    function punkter($AktivitetID, $TidsStempel) {
	  $sql = $this->lagSelectSql("punkter", $AktivitetID, $TidsStempel);
	  
	  $data = "";
	  $omrader = $this->db->query($sql);
	  while (isset($TidsStempel) && $omrader->num_rows() == 0)
	  {
		sleep(1);
	    $omrader = $this->db->query($sql);
	  }
	  
	  foreach ($omrader->result() as $omrade)
		$data[] = $omrade;
	  
	  return $data;      
    }

    // Lagrer ett punkt i databasen, eller oppdaterer ett eksisterende. data er ett array med data.
    function lagrepunkt($data) {
		$tidsstempel = new DateTime();
		if (!($data->ID))
		{
			$data->ID = $this->finnNesteID("punkter");
			$handling = "I";
		}
		else
			$handling = "U";

		$sql = 
"INSERT INTO punkter(ID, AktivitetID, TidsStempel, Handling, Navn, Beskrivelse, Symbol, Radius, Punkt)
VALUES(" . $data->ID . ", " . $data->AktivitetID . ", '" . $tidsstempel->format('Y-m-d H:i:s.u') . "', '" . $handling . "', '" . $data->Navn . "', '" . $data->Beskrivelse . "', '" . $data->Symbol . "', " . (empty($data->Radius) ? "NULL" : $data->Radius) . ", '" . $data->Punkt . "')";
		$this->db->query($sql);

		$data->Handling = $handling;
		$data->TidsStempel = $tidsstempel;
		return $data;
    }

	function slettpunkt($data) {
		$tidsstempel = new DateTime();
		if ($data->ID)
		{
			$handling = "D";
			$sql = 
"INSERT INTO punkter(ID, AktivitetID, TidsStempel, Handling)
VALUES(" . $data->ID . ", " . $data->AktivitetID . ", '" . $tidsstempel->format('Y-m-d H:i:s.u') . "', '" . $handling . "')";
		$this->db->query($sql);
		}

		$data->TidsStempel = $tidsstempel;
		$data->Handling = $handling;
		return $data;
	}
    // Returnerer ett array med teiger knyttet til en aktivitet. AktivitetID er ett heltall.
    function teiger($AktivitetID, $TidsStempel) {
		$sql = $this->lagSelectSql("teiger", $AktivitetID, $TidsStempel);
	  
		$data = "";
		$omrader = $this->db->query($sql);
		while (isset($TidsStempel) && $omrader->num_rows() == 0)
		{
			sleep(1);
			$omrader = $this->db->query($sql);
		}

		foreach ($omrader->result() as $omrade)
			$data[] = $omrade;

		return $data;   
    }

    // Lagrer ett spor i databasen, eller oppdaterer ett eksisterende. data er ett array med data.
    function lagreteig($data) {
		$tidsstempel = new DateTime();
		if (!($data->ID))
		{
			$data->ID = $this->finnNesteID("teiger");
			$handling = "I";
		}
		else
			$handling = "U";

		$sql = 
"INSERT INTO teiger(ID, AktivitetID, TidsStempel, Handling, Navn, Beskrivelse, FyllFarge, FyllGjennomsiktighet, StrekFarge, StrekTykkelse, Polygon)
VALUES(" . $data->ID . ", " . $data->AktivitetID . ", '" . $tidsstempel->format('Y-m-d H:i:s.u') . "', '" . $handling . "', '" . $data->Navn . "', '" . $data->Beskrivelse . "', '" . $data->FyllFarge . "', " . (empty($data->FyllGjennomsiktighet) ? "NULL" : $data->FyllGjennomsiktighet) . ", '" . $data->StrekFarge . "', " . (empty($data->StrekTykkelse) ? "NULL" : $data->StrekTykkelse) . ", '" . $data->Polygon . "')";
		$this->db->query($sql);

		$data->Handling = $handling;
		$data->TidsStempel = $tidsstempel;
		return $data;
    }
	function slettteig($data) {
		$tidsstempel = new DateTime();
		if ($data->ID)
		{
			$handling = "D";
			$sql = 
"INSERT INTO teiger(ID, AktivitetID, TidsStempel, Handling)
VALUES(" . $data->ID . ", " . $data->AktivitetID . ", '" . $tidsstempel->format('Y-m-d H:i:s.u') . "', '" . $handling . "')";
		$this->db->query($sql);
		}

		$data->TidsStempel = $tidsstempel;
		$data->Handling = $handling;
		return $data;
	}

    // Returnerer ett array med sporlogger knyttet til en aktivitet. AktivitetID er ett heltall.
    function sporlogger($AktivitetID, $TidsStempel) {
		$sql = $this->lagSelectSql("sporlogger", $AktivitetID, $TidsStempel);
	  
		$data = "";
		$omrader = $this->db->query($sql);
		while (isset($TidsStempel) && $omrader->num_rows() == 0)
		{
			sleep(1);
			$omrader = $this->db->query($sql);
		}

		foreach ($omrader->result() as $omrade)
			$data[] = $omrade;

		return $data;   
    }

    // Lagrer ett spor i databasen, eller oppdaterer ett eksisterende. data er ett array med data.
    function lagresporlogg($data) {
		$tidsstempel = new DateTime();
		if (!($data->ID))
		{
			$data->ID = $this->finnNesteID("sporlogger");
			$handling = "I";
		}
		else
			$handling = "U";

		$sql = 
"INSERT INTO sporlogger(ID, AktivitetID, TidsStempel, Handling, Navn, Beskrivelse, StrekFarge, StrekTykkelse, StrekGjennomsiktighet, Spor)
VALUES(" . $data->ID . ", " . $data->AktivitetID . ", '" . $tidsstempel->format('Y-m-d H:i:s.u') . "', '" . $handling . "', '" . $data->Navn . "', '" . $data->Beskrivelse . "', '" . $data->StrekFarge . "', " . (empty($data->StrekTykkelse) ? "NULL" : $data->StrekTykkelse) . ", " . (empty($data->StrekGjennomsiktighet) ? "NULL" : $data->StrekGjennomsiktighet) . ", '" . $data->Spor . "')";
		$this->db->query($sql);

		$data->Handling = $handling;
		$data->TidsStempel = $tidsstempel;
		return $data;
    }
	function slettsporlogg($data) {
		$tidsstempel = new DateTime();
		if ($data->ID)
		{
			$handling = "D";
			$sql = 
"INSERT INTO sporlogger(ID, AktivitetID, TidsStempel, Handling)
VALUES(" . $data->ID . ", " . $data->AktivitetID . ", '" . $tidsstempel->format('Y-m-d H:i:s.u') . "', '" . $handling . "')";
		$this->db->query($sql);
		}

		$data->TidsStempel = $tidsstempel;
		$data->Handling = $handling;
		return $data;
	}
	// Returnerer en SELECT fra valgt tabell, og filtrerer bort historiske data
	function lagSelectSql($TabellNavn, $AktivitetID, $TidsStempel) {
		$sql = "SELECT * FROM " . $TabellNavn . " AS t WHERE t.AktivitetID=" . $AktivitetID . " AND NOT EXISTS (SELECT * FROM " . $TabellNavn . " AS t2 WHERE t2.ID = t.ID AND t2.TidsStempel > t.TidsStempel)";	  
		if (!empty($TidsStempel))
			$sql = $sql . " AND t.TidsStempel > '" . $TidsStempel . "'";
		else
			$sql = $sql . " AND t.Handling != 'D' ";
		$sql = $sql . " ORDER BY t.TidsStempel ";
		return $sql;
	}
	// Slår opp høyeste ID og returnerer en verdi en høyere. Returnerer 1 hvis ingen finnes
	function finnNesteID($TabellNavn)
	{
		$max = $this->db->query("SELECT max(ID) AS MaxID FROM " . $TabellNavn);
		if ($maxid = $max->row())
			return $maxid->MaxID + 1;
		else
			return 1;
	}
  }
