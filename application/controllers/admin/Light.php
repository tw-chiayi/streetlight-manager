<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Light extends MY_ADMIN_Controller {
  var $_enable_cookie_write_methods = [];

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("lightModel");
  }

  public function index(){

    $unCity = $_SESSION["user"]->city;
    $city_counts = $this->lightModel->get_city_counts($unCity);
    $reports =  $this->lightModel->get_unhandled_city_reports($unCity);

    $this->load->view('admin/light/index',
      [
        "city_counts" => $city_counts,
        "city" => $unCity,
        "reports" => $reports
      ] );
  }

  public function reports(){

    $unCity = $_SESSION["user"]->city;
    $reports =  $this->lightModel->get_city_reports($unCity);

    $this->load->view('admin/light/reports',
      [
        "city" => $unCity,
        "reports" => $reports
      ] );
  }

  public function repair(){
    $unCity = $_SESSION["user"]->city;
    $lights = $this->lightModel->get_repair_light_by_city($unCity);

    $this->load->view('admin/light/repair',
      [
        "city" => $unCity,
        "lights" => $lights
      ] );
  }

  public function set_report_status($report,$status){
    $this->lightModel->set_report_status($report,$status);

    redirect("admin/light/index");
  }

  public function action_submit(){
    $unCity = $_SESSION["user"]->city;

    $ids = $this->input->post("ids"); //array
    $inputs = [];
    foreach($ids as $id){
      $inputs[] = $id;
    }
    $lights = $this->lightModel->get_repair_light_by_ids_city($unCity,$inputs);

    if($this->input->post("action") == "1"){
      header('Content-type:application/force-download'); //告訴瀏覽器 為下載 
      header('Content-Transfer-Encoding: Binary'); //編碼方式
      header('Content-Disposition:attachment;filename=維修座標點- '.date("Y.m.d").".gpx"); //檔名

      $this->load->view('xml/gpx_template',
        [
          "city" => $unCity,
          "lights" => $lights
        ] );
    }else{
      $lights = $this->lightModel->fix_light_by_ids($unCity,$inputs);
      redirect("admin/light/repair");
    }
    

  }

}
