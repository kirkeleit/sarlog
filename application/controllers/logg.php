<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Logg extends CI_Controller {

    public function index() {
      $data['LoggID'] = $this->uri->segment(3);
      $this->load->view('logg',$data);
    }

    /*public function utskrift() {
      $this->load->model('Sambandslogg_modell');
      $data['Logg'] = $this->Sambandslogg_modell->LoggInfo($this->uri->segment(3));
      $data['Linjer'] = $this->Sambandslogg_modell->Logglinjer($this->uri->segment(3));
      $this->load->view('utskriftlogg',$data);
    }*/

  }
