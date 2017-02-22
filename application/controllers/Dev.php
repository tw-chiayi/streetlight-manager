<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dev extends MY_Controller {

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -
   *    http://example.com/index.php/welcome/index
   *  - or -
   * Since this controller is set as the default controller in
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see https://codeigniter.com/user_guide/general/urls.html
   */
  public function load()
  {
    session_write_close();
    $lucao = file_get_contents(__DIR__."/../../data/lucao.geojson");


    $this->load->database();

    $obj = json_decode($lucao);
    foreach($obj->features as $ind => $light){
      if($ind < 3574){
        continue;
      }
      $name = $light->properties->name;
      $ctime = $light->properties->cmt;

      if(isset($light->properties->time)){
        $ctime = $light->properties->time;
      }

      $type = $light->properties->sym;

      // ["鹿東", "後寮", "鹿草", "豊稠", "西井", "重寮", "施家", "下潭", "光潭", "碧潭", "竹山", "松竹", "三角", "後堀", "下麻"] 

      $this->load->model("lightModel");
      if($ctime == $name || $ctime == "道路" || $ctime =="後塘國小"){
        $ctime = null;
        if($ctime == "後塘國小"){
          $name = $name." ".$ctime;
        }
      }

      $this->lightModel->insert([
        "name" => $name,
        "ctime" => $ctime,
        "mtime" => $ctime,
        "lat" => $light->geometry->coordinates[0],
        "lng" => $light->geometry->coordinates[1],
        "height" => $light->geometry->coordinates[2],
        "status" => 0,
        "type" => $type
      ]);

      // $latlng = json_encode($light->geometry->coordinates); 

      // echo "$name $ctime $type $latlng <br />";
    }
    // die(var_dump($obj->features));

  }

}
