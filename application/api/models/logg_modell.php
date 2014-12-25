<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Logg_modell extends CI_Model {

    function nylogg($data) {
      $this->db->query("INSERT INTO logger (LoggtypeID,DatoRegistrert,DatoEndret,Kallesignal) VALUES (".$data['LoggtypeID'].",Now(),Now(),'".$data['Kallesignal']."')");
      $LoggID = $this->db->insert_id();
      $this->db->query("INSERT INTO logglinjer (LoggID,LinjetypeID,DatoRegistrert,DatoMelding,Melding) VALUES (".$LoggID.",0,Now(),Now(),'Opprettet logg.')");
      //$qlagsliste = $this->db->query("SELECT * FROM lag WHERE (Kallesignal='".$data['Kallesignal']."') AND (AktivitetID=".$data['AktivitetID'].") LIMIT 1");
      //if ($qlagsliste->num_rows() == 0) {
        //$this->db->query("INSERT INTO lag (AktivitetID,Kallesignal,Navn,DatoRegistrert,DatoEndret) VALUES (".$data['AktivitetID'].",'".$data['Kallesignal']."','Lag ".$data['Kallesignal']."',Now(),Now()) LIMIT 1");
      //}
      return $LoggID;
    }

    function logger() {
      $qlogger = $this->db->query("SELECT * FROM logger ORDER BY ID");
      foreach ($qlogger->result() as $qlogg) {
        $logg['ID'] = $qlogg->ID;
        if ($qlogg->LoggtypeID == 0) {
          $logg['Navn'] = "AL/".date("d.m.Y",strtotime($qlogg->DatoRegistrert))."/".$logg['ID'];
        } elseif ($qlogg->LoggtypeID == 1) {
          $logg['Navn'] = "SL/".date("d.m.Y",strtotime($qlogg->DatoRegistrert))."/".$logg['ID'];
        } elseif ($qlogg->LoggtypeID == 2) {
          $logg['Navn'] = "SAN/".date("d.m.Y",strtotime($qlogg->DatoRegistrert))."/".$logg['ID'];
        }
        $logg['LoggtypeID'] = $qlogg->LoggtypeID;
        $logg['DatoRegistrert'] = $qlogg->DatoRegistrert;
        $logg['DatoEndret'] = $qlogg->DatoEndret;
        $qlinjer = $this->db->query("SELECT * FROM logglinjer WHERE (LoggID=".$qlogg->ID.")");
        $logg['Logglinjer'] = $qlinjer->num_rows();
        unset($qlinjer);
        $logger[] = $logg;
        unset($logg);
      }
      return $logger;
    }

    function loggdata($LoggID) {
      $qlogger = $this->db->query("SELECT * FROM logger WHERE (ID=".$LoggID.") LIMIT 1");
      if ($qlogg = $qlogger->row()) {
        $data['ID'] = $qlogg->ID;
        if ($qlogg->LoggtypeID == 0) {
          $data['Navn'] = "AL/".date("Y",strtotime($qlogg->DatoRegistrert))."-".$data['ID'];
        } elseif ($qlogg->LoggtypeID == 1) {
          $data['Navn'] = "SL/".date("Y",strtotime($qlogg->DatoRegistrert))."-".$data['ID'];
        } elseif ($qlogg->LoggtypeID == 2) {
          $data['Navn'] = "SAN/".date("Y",strtotime($qlogg->DatoRegistrert))."-".$data['ID'];
        }
        $data['LoggtypeID'] = $qlogg->LoggtypeID;
        $data['Kallesignal'] = $qlogg->Kallesignal;
        return $data;
      }
    }

    function linjer($LoggID) {
      $qlinjer = $this->db->query("SELECT * FROM logglinjer WHERE (LoggID=".$LoggID.") ORDER BY DatoMelding ASC, ID ASC");
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
      $logg = $this->loggdata($LoggID);
      if (mktime(substr($data['DTG'],2,2),substr($data['DTG'],4,2),0,date('n'),substr($data['DTG'],0,2),date('Y')) <= time()) {
        $data['DatoMelding'] = date("Y-m-d H:i:s",mktime(substr($data['DTG'],2,2),substr($data['DTG'],4,2),0,date('n'),substr($data['DTG'],0,2),date('Y')));
      } else {
        $data['DatoMelding'] = date("Y-m-d H:i:s");
      }
      $this->db->query("INSERT INTO logglinjer (LoggID,LinjetypeID,DatoRegistrert,DatoMelding,Fra,Til,Melding) VALUES (".$LoggID.",1,Now(),'".$data['DatoMelding']."','".$data['Fra']."','".$data['Til']."','".$data['Melding']."')");
      $this->db->query("UPDATE logger SET DatoEndret=Now() WHERE ID=".$LoggID." LIMIT 1");
    }

  }
