<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Aktivitet extends CI_Controller {

    public function aktiviteter() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Aktivitet_modell');
      $data['data'] = $this->Aktivitet_modell->aktiviteter();
      $this->load->view('api_json',$data);
    }

  }

/* End of file aktivitet.php */
/* Location: ./application/controllers/aktivitet.php */
