<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Portfolio_model', 'portfolio');
		$this->load->helper(array('url', 'form', 'text'));
	}

	public function index()
	{
		$data = array(
			'profile' => $this->portfolio->get_profile(),
			'skills' => $this->portfolio->get_skills(),
			'projects' => $this->portfolio->get_projects(),
			'message_sent' => $this->session->flashdata('message_sent')
		);
		$this->load->view('public_home', $data);
	}

	public function contact()
	{
		if ($this->input->method() !== 'post') {
			redirect('/#contact');
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Nama', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[150]');
		$this->form_validation->set_rules('message', 'Pesan', 'required|trim|max_length[2000]');

		if ($this->form_validation->run()) {
			$this->portfolio->save_message(array(
				'name' => $this->input->post('name', TRUE),
				'email' => $this->input->post('email', TRUE),
				'message' => $this->input->post('message', TRUE)
			));
			$this->session->set_flashdata('message_sent', 'Pesan Anda sudah masuk. Saya akan segera menghubungi Anda.');
		}
		redirect('/#contact');
	}
}
