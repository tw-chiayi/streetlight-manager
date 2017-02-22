<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
  var $_enable_cookie_write_methods = [];
  public function __construct()
  {
    parent::__construct();
    $this->load->library("session");
    $this->_initvars();
    
  }

  public function _initvars(){
  }

  public function _is_login(){
    return isset($_SESSION["user"]);
  }

  public function _toGzip($path){

    // Name of compressed gz file 
    $gzfile = $path.".gz";

    // Open the gz file (w9 is the highest compression)
    $fp = gzopen($gzfile, 'w9');

    // Compress the file
    gzwrite ($fp, file_get_contents($path));

    //close the file after compression is done
    gzclose($fp);

    return $gzfile;
  }


  protected function return_success_json ($obj,$header = false){
    if($header){
      header('Content-Type: application/json');
    }
    return $this->return_json(new ReturnMessage(true,0, null, $obj));
  }

  protected function return_error($code,$msg,$header = false){
    if($header){
      header('Content-Type: application/json');
    }       
    return $this->return_json(new ReturnMessage(false,$code,$msg, null));
  }

  protected function return_json ($obj,$header = false){
    if($header){
      header('Content-Type: application/json');
    }       
    echo json_encode($obj);
    return true;
  }  



  public function _showMessage($msgHeader,$body,$nextURL = "/",$nextWord ="回首頁"){
    return $this->load->view("message",[
      "pageTitle" => $msgHeader,
      "msgHeader" => $msgHeader,
      "msgBody" => $body,
      "msgNext" => $nextURL,
      "msgNextWord" => $nextWord
    ]);
  }
  
}

include("MY_ADMIN_Controller.php");

