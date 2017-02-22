<?php 

class MY_ADMIN_Controller extends MY_Controller {

  public function _load_view($path,$args,$ret = false){
    return $this->load->view('admin/'.$path,$args,$ret = false);
  }
  // NOTE: who want to overwrite the _remap 
  // have to rewrite the enable_cookie_write_methods also.
  public function _remap($method, $params = [])
  {

    if( $this->_enable_cookie_write_methods == null ||
       ! in_array($method,$this->_enable_cookie_write_methods)){
      session_write_close();
    }

    $mem = null;

    $this->load->database();
    // $this->load->model("areaModel");

    // $areas = $this->areaModel->get_area_simple_list();
    // $this->_areas = $areas;

    // $this->load->vars([ "_areas" => $areas ]);

    $allows = ["signin","signing","logout"];

    if(!in_array($method,$allows)){

      // $mem_sn = $this->_get_user_sn();
      if(!$this->_is_login()){
        redirect(site_url("admin/user/signin"));
        return false;  
      }
      $mem_sn = $_SESSION["user"]->id;

      if($mem_sn == null){
        return $this->_return_404();
      }

      $this->load->vars([ "mem" => $_SESSION["user"],
        "is_login" => $this->_is_login() ]);
      $this->_mem = $_SESSION["user"];
    }

    if (method_exists($this, $method))
    {
      return call_user_func_array([$this, $method], $params);
    }

    show_404();
    return false;
  }


}