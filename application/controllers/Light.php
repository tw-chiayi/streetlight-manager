<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Light extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model("lightModel");
	}

	public function map()
	{
		$this->load->view('light/map',
			[
				"points" => $this->lightModel->get_all_for_map(	)
			] );
		session_write_close();
	}

	public function report($point_id){

		$point = $this->lightModel->get($point_id);
		if($point == null){
			return show_404();
		}

		$this->load->view('light/report',
			[
				"point" => $point,
				"points" => [$point]
			] );
		session_write_close();
	}


	public function reporting(){
		$point_id = $this->input->post("point_id");

		$point = $this->lightModel->get($point_id);
		if($point == null){
			return show_404();
		}	

		$fields= ["name","contact","comment","email"];

		$data = ["light_id" => $point_id,"status" => 0];
		foreach($fields as $key){
			$data[$key] = $this->input->post($key);
		}

		$report_id = $this->lightModel->insert_report($data);

		redirect("light/reported/".$report_id);
		session_write_close();
	}

	public function reported(){
		$this->load->view('light/reported');
		session_write_close();

	}

}
