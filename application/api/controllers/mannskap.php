<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Mannskap extends CI_Controller {

    public function lagsliste() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Mannskap_modell');
      $data['data'] = $this->Mannskap_modell->lagsliste($this->input->get('aktivitetid'));
      $this->load->view('api_json',$data);
    }

  }

/* End of file mannskap.php */
/* Location: ./application/controllers/mannskap.php */
