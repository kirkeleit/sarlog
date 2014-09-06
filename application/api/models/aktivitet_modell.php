<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Aktivitet_modell extends CI_Model {

    function aktiviteter() {
      $qaktiviteter = $this->db->query("SELECT * FROM aktiviteter WHERE (Lukket=0) ORDER BY ID");
      if ($qaktiviteter->num_rows() > 0) {
        foreach ($qaktiviteter->result() as $qaktivitet) {
          $aktivitet['ID'] = $qaktivitet->ID;
          $aktivitet['Navn'] = $qaktivitet->Navn;
          $aktivitet['Beskrivelse'] = $qaktivitet->Beskrivelse;
          $aktivitet['DatoStart'] = $qaktivitet->DatoStart;
          $aktivitet['DatoSlutt'] = $qaktivitet->DatoSlutt;
          $aktivitet['Øvelse'] = $qaktivitet->Øvelse;
          $aktivitet['Lukket'] = $qaktivitet->Lukket;
          unset($qaktivitet);
          $aktiviteter[] = $aktivitet;
          unset($aktivitet);
        }
        $data['Antall'] = $qaktiviteter->num_rows();
        $data['Aktiviteter'] = $aktiviteter;
      } else {
        $data['Antall'] = 0;
      }
      return $data;
    }

  }
