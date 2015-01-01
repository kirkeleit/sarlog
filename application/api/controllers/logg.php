<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Logg extends CI_Controller {

    public function logg_opprett() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Logg_modell');
      $data['TypeID'] = $this->input->post('TypeID');
      $data['Tittel'] = $this->input->post('Tittel');
      $data['Beskrivelse'] = $this->input->post('Beskrivelse');
      $data['Kallesignal'] = $this->input->post('Kallesignal');
      $LoggID = $this->Logg_modell->logg_opprett($data);
      echo $LoggID;
    }

    public function logg_liste() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Logg_modell');
      $data['data'] = $this->Logg_modell->logg_liste();
      $this->load->view('api_json',$data);
    }

    public function logg_data() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Logg_modell');
      $data['data'] = $this->Logg_modell->logg_data($this->input->get('id'));
      $this->load->view('api_json',$data);
    }

    public function logg_avslutt() {
      $this->output->set_header('Access-Control-Allow-Origin: *');
      $this->load->model('Logg_modell');
      $this->Logg_modell->logg_avslutt($this->input->get('id'));
      echo $LoggID;
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
      $data['DTG'] = $this->input->post('DTG');
      $data['Fra'] = $this->input->post('Fra');
      $data['Til'] = $this->input->post('Til');
      $data['Melding'] = $this->input->post('Melding');
      $data['Ekstra'] = $this->input->post('Ekstra');
      $this->Logg_modell->lagrelinje($this->input->get('loggid'), $data);
      //$this->load->view('api_json');
    }

  }

/* End of file logg.php */
/* Location: ./application/controllers/logg.php */
