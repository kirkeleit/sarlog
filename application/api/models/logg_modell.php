<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Logg_modell extends CI_Model {

    function logg_opprett($data) {
      $LoggID = uniqid(rand());
      $this->db->query("INSERT INTO logger (ID,TypeID,DatoOpprettet,Tittel,Beskrivelse,Kallesignal) VALUES ('".$LoggID."',".$data['TypeID'].",Now(),'".$data['Tittel']."','".$data['Beskrivelse']."','".$data['Kallesignal']."')");
      $this->db->query("INSERT INTO logglinjer (LoggID,DatoRegistrert,DatoMelding,Melding) VALUES ('".$LoggID."',Now(),Now(),'Opprettet logg ".$LoggID.".')");
      return $LoggID;
    }

    function logg_liste() {
      $qlogger = $this->db->query("SELECT * FROM logger ORDER BY ID ASC");
      foreach ($qlogger->result() as $qlogg) {
        $logg['ID'] = $qlogg->ID;
        if ($qlogg->TypeID == 0) {
          //$logg['Navn'] = "AL/".date("d.m.Y",strtotime($qlogg->DatoRegistrert))."/".$logg['ID'];
          $logg['Type'] = "AL";
        } elseif ($qlogg->TypeID == 1) {
          //$logg['Navn'] = "SL/".date("d.m.Y",strtotime($qlogg->DatoRegistrert))."/".$logg['ID'];
          $logg['Type'] = "SL";
        } elseif ($qlogg->TypeID == 2) {
          //$logg['Navn'] = "SAN/".date("d.m.Y",strtotime($qlogg->DatoRegistrert))."/".$logg['ID'];
          $logg['Type'] = "SAN";
        }
        if ($qlogg->Tittel == "") { $logg['Tittel'] = "Uten navn"; } else { $logg['Tittel'] = $qlogg->Tittel; }
        if ($qlogg->Beskrivelse == "") { $logg['Beskrivelse'] = "Ingen beskrivelse"; } else { $logg['Beskrivelse'] = $qlogg->Beskrivelse; }
        $logg['TypeID'] = $qlogg->TypeID;
        $logg['DatoOpprettet'] = $qlogg->DatoOpprettet;
        $logg['DatoAvsluttet'] = $qlogg->DatoAvsluttet;
        $qlinjer = $this->db->query("SELECT * FROM logglinjer WHERE (LoggID='".$qlogg->ID."')");
        $logg['Linjer'] = $qlinjer->num_rows();
        unset($qlinjer);
        $logger[] = $logg;
        unset($logg);
      }
      if (isset($logger)) {
      return $logger;
      }
    }

    function logg_data($LoggID) {
      $qlogger = $this->db->query("SELECT * FROM logger WHERE (ID='".$LoggID."') LIMIT 1");
      if ($qlogg = $qlogger->row()) {
        $data['ID'] = $qlogg->ID;
        if ($qlogg->TypeID == 0) {
          //$data['Navn'] = "AL/".date("Y",strtotime($qlogg->DatoRegistrert))."-".$data['ID'];
          $data['Type'] = "AL";
        } elseif ($qlogg->TypeID == 1) {
          //$data['Navn'] = "SL/".date("Y",strtotime($qlogg->DatoRegistrert))."-".$data['ID'];
          $data['Type'] = "SL";
        } elseif ($qlogg->TypeID == 2) {
          //$data['Navn'] = "SAN/".date("Y",strtotime($qlogg->DatoRegistrert))."-".$data['ID'];
          $data['Type'] = "SAN";
        }
        $data['DatoOpprettet'] = $qlogg->DatoOpprettet;
        $data['DatoAvsluttet'] = $qlogg->DatoAvsluttet;
        $data['TypeID'] = $qlogg->TypeID;
        $data['Tittel'] = $qlogg->Tittel;
        $data['Beskrivelse'] = $qlogg->Beskrivelse;
        $data['Kallesignal'] = $qlogg->Kallesignal;
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
