<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'core/loggobject.php');
require_once(APPPATH.'core/logglinje.php');

  class Logg_modell extends CI_Model {
  	
	protected $loggTyper = array(0 => "AL", 1 => "SL", 2 => "SAN");
	
    function logg_opprett($data) 
    {
    	$data["DatoRegistrert"] = $data["DatoMelding"] = $data['DatoOpprettet'] = new DateTime();
		$logg = new LoggObject(null, $data["TypeID"], $data["DatoOpprettet"], $data["Tittel"], $data["Beskrivelse"], $data["Kallesignal"]);
	  	$this->db->insert('logger', $logg);
		$this->db->insert('logglinjer', new LoggLinje($logg->ID));
		return $logg->ID;
    }

    function logg_liste() 
    {
    	$logger = array();
      	$qlogger = $this->db->query("SELECT * FROM logger ORDER BY ID ASC");
      	foreach ($qlogger->result() as $qlogg) 
      	{
			$qlogg->Type = $this->loggTyper[$qlogg->TypeID];
			$qlogg->Tittel = $qlogg->Tittel ? $qlogg->Tittel : "Uten Navn";
			$qlogg->Beskrivelse = $qlogg->Beskrivelse ? $qlogg->Beskrivelse : "Ingen beskrivelse";
			$qlogg->Linjer = $this->db->from("logglinjer")->where("LoggID", $qlogg->ID)->count_all_results();
    	    $logger[] = (array)$qlogg;
    	  }
		return $logger;
   	 }

    function logg_data($LoggID) {
      $qlogger = $this->db->query("SELECT * FROM logger WHERE (ID='".$LoggID."') LIMIT 1");
      if ($qlogg = $qlogger->row()) {
		$data = (array) $qlogg;
		$data['Type'] = $this->loggTyper[$qlogg->TypeID];
        return $data;
      }
    }

    function logg_avslutt($LoggID) {
      $this->db->query("UPDATE logger SET DatoAvsluttet=Now() WHERE ID='".$LoggID."' LIMIT 1");
      $this->db->query("INSERT INTO logglinjer (LoggID,DatoRegistrert,DatoMelding,Melding) VALUES ('".$LoggID."',Now(),Now(),'Avsluttet logg ".$LoggID.".')");
      return $LoggID;
    }

    function linjer($LoggID) {
      $qlinjer = $this->db->query("SELECT * FROM logglinjer WHERE (LoggID='".$LoggID."') ORDER BY DatoMelding ASC, ID ASC");
      if ($qlinjer->num_rows() > 0) {
        if ($qlinjer->num_rows() > 999) {
          $Pads = 4;
        } elseif ($qlinjer->num_rows() > 99) {
          $Pads = 3;
        } else {
          $Pads = 2;
        }
        $LinjeNr = 0;
        $DatoSisteMelding = 0;
        foreach ($qlinjer->result() as $qlinje) {
          $LinjeNr++;
          $linje['Nr'] = str_pad($LinjeNr,$Pads,"0",STR_PAD_LEFT);
          $linje['ID'] = $qlinje->ID;
          $linje['TypeID'] = $qlinje->LinjetypeID;
          $linje['DatoRegistrert'] = $qlinje->DatoRegistrert;
          $linje['DatoMelding'] = $qlinje->DatoMelding;
          $linje['DTG'] = date("dHi",strtotime($qlinje->DatoMelding));
          $DatoSisteMelding = strtotime($qlinje->DatoMelding);
          $linje['Fra'] = $qlinje->Fra;
          $linje['Til'] = $qlinje->Til;
          $linje['Melding'] = $qlinje->Melding;
          $linjer[] = $linje;
          unset($qlinje);
          unset($linje);
        }
        $data['Antall'] = $qlinjer->num_rows();
        $data['DatoSisteMelding'] = $DatoSisteMelding;
        $data['Linjer'] = $linjer;
      } else {
        $data['Antall'] = 0;
        $data['DatoSisteMelding'] = 0;
      }
      return $data;
    }

    function lagrelinje($LoggID, $data) {
      //$logg = $this->logg_data($LoggID);
      if (mktime(substr($data['DTG'],2,2),substr($data['DTG'],4,2),0,date('n'),substr($data['DTG'],0,2),date('Y')) <= time()) {
        $data['DatoMelding'] = date("Y-m-d H:i:s",mktime(substr($data['DTG'],2,2),substr($data['DTG'],4,2),0,date('n'),substr($data['DTG'],0,2),date('Y')));
      } else {
        $data['DatoMelding'] = date("Y-m-d H:i:s");
      }
      $this->db->query("INSERT INTO logglinjer (LoggID,LinjetypeID,DatoRegistrert,DatoMelding,Fra,Til,Melding) VALUES ('".$LoggID."',1,Now(),'".$data['DatoMelding']."','".$data['Fra']."','".$data['Til']."','".$data['Melding']."')");
      //$this->db->query("UPDATE logger SET DatoEndret=Now() WHERE ID=".$LoggID." LIMIT 1");
    }

  }
