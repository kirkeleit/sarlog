<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Logg extends CI_Controller {

    public function nylogg() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Logg_modell');
      $data['LoggtypeID'] = $this->input->post('LoggtypeID');
      $data['Beskrivelse'] = $this->input->post('Beskrivelse');
      $data['Kallesignal'] = $this->input->post('Kallesignal');
      $LoggID = $this->Logg_modell->nylogg($data);
      echo $LoggID;
    }

    public function logger() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Logg_modell');
      $data['data'] = $this->Logg_modell->logger();
      $this->load->view('api_json',$data);
    }

    public function loggdata() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Logg_modell');
      $data['data'] = $this->Logg_modell->loggdata($this->input->get('loggid'));
      $this->load->view('api_json',$data);
    }

    public function linjer() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Logg_modell');
      $data['data'] = $this->Logg_modell->linjer($this->input->get('loggid'));
      $this->load->view('api_json',$data);
    }

    public function lagrelinje() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Logg_modell');
      $data['OpprettLag'] = $this->input->post('OpprettLagAutomatisk');
      $data['DTG'] = $this->input->post('DTG');
      $data['Fra'] = $this->input->post('Fra');
      $data['Til'] = $this->input->post('Til');
      $data['Melding'] = $this->input->post('Melding');
      $this->Logg_modell->lagrelinje($this->input->get('loggid'), $data);
      //$this->load->view('api_json');
    }

  }

/* End of file logg.php */
/* Location: ./application/controllers/logg.php */
