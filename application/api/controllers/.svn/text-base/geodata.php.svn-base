<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Geodata extends CI_Controller {

    public function omrader() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
      $data['data'] = $this->Geodata_modell->omrader($this->input->get('aktivitetid'), $this->input->get('tidsstempel'));
      $this->load->view('api_json',$data);
    }
    public function lagreomrade() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
	  $input_data = json_decode(trim(file_get_contents('php://input')));
      $data['data'] = $this->Geodata_modell->lagreomrade($input_data);
      $this->load->view('api_json',$data);
    }
    public function slettomrade() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
	  $input_data = json_decode(trim(file_get_contents('php://input')));
      $data['data'] = $this->Geodata_modell->slettomrade($input_data);
      $this->load->view('api_json',$data);
    }

    public function punkter() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
      $data['data'] = $this->Geodata_modell->punkter($this->input->get('aktivitetid'), $this->input->get('tidsstempel'));
      $this->load->view('api_json',$data);
    }
    public function lagrepunkt() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
	  $input_data = json_decode(trim(file_get_contents('php://input')));
      $data['data'] = $this->Geodata_modell->lagrepunkt($input_data);
      $this->load->view('api_json',$data);
    }
    public function slettpunkt() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
	  $input_data = json_decode(trim(file_get_contents('php://input')));
      $data['data'] = $this->Geodata_modell->slettpunkt($input_data);
      $this->load->view('api_json',$data);
    }

    public function teiger() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
      $data['data'] = $this->Geodata_modell->teiger($this->input->get('aktivitetid'), $this->input->get('tidsstempel'));
      $this->load->view('api_json',$data);
    }
    public function lagreteig() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
	  $input_data = json_decode(trim(file_get_contents('php://input')));
      $data['data'] = $this->Geodata_modell->lagreteig($input_data);
      $this->load->view('api_json',$data);
    }
    public function slettteig() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
	  $input_data = json_decode(trim(file_get_contents('php://input')));
      $data['data'] = $this->Geodata_modell->slettteig($input_data);
      $this->load->view('api_json',$data);
    }

    public function sporlogger() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
      $data['data'] = $this->Geodata_modell->sporlogger($this->input->get('aktivitetid'), $this->input->get('tidsstempel'));
      $this->load->view('api_json',$data);
    }
    public function lagresporlogg() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
	  $input_data = json_decode(trim(file_get_contents('php://input')));
      $data['data'] = $this->Geodata_modell->lagresporlogg($input_data);
      $this->load->view('api_json',$data);
    }
    public function slettsporlogg() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Geodata_modell');
	  $input_data = json_decode(trim(file_get_contents('php://input')));
      $data['data'] = $this->Geodata_modell->slettsporlogg($input_data);
      $this->load->view('api_json',$data);
    }

  }

/* End of file geodata.php */
/* Location: ./application/controllers/geodata.php */
