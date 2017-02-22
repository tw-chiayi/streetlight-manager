<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model("lightModel");
	}

	public function index(){


		$this->load->view('manager/index',
			[] );
		session_write_close();
	}


	public function city($city){
		$unCity = rawurldecode($city);
		$city_counts = $this->lightModel->get_city_counts($unCity);

		$reports =  $this->lightModel->get_city_reports($unCity);

		$this->load->view('manager/city',
			[
				"city_counts" => $city_counts,
				"city" => $unCity,
				"reports" => $reports
			] );
		session_write_close();
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


}
