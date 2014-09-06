<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Mannskap_modell extends CI_Model {

    function lagsliste($AktivitetID) {
      $SistEndret = 0;
      $qlagsliste = $this->db->query("SELECT * FROM lag WHERE (AktivitetID=".$AktivitetID.") ORDER BY ID");
      if ($qlagsliste->num_rows() > 0) {
        foreach ($qlagsliste->result() as $qlag) {
          $lag['ID'] = $qlag->ID;
          $lag['Kallesignal'] = $qlag->Kallesignal;
          $lag['Navn'] = $qlag->Navn;
          $lag['DatoRegistrert'] = $qlag->DatoRegistrert;
          $lag['DatoEndret'] = $qlag->DatoEndret;
          $lag['DatoSisteMelding'] = $qlag->DatoSisteMelding;
          $lag['SisteMeldingMinutter'] = floor((time()-strtotime($qlag->DatoSisteMelding))/60);
          $lag['SambandVent'] = $qlag->SambandVent;
          if (strtotime($qlag->DatoEndret) > $SistEndret) {
            $SistEndret = strtotime($qlag->DatoEndret);
          }
          unset($qlag);
          $lagsliste[] = $lag;
          unset($lag);
        }
        $data['Antall'] = $qlagsliste->num_rows();
        $data['SistEndret'] = $SistEndret;
        $data['Lagsliste'] = $lagsliste;
      } else {
        $data['Antall'] = 0;
        $data['SistEndret'] = 0;
      }
      return $data;
    }

  }
