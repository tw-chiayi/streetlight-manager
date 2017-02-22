<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_ADMIN_Controller {
  var $_enable_cookie_write_methods = ["signing"];

  public function index(){

    $this->load->view('admin/user/index',[]);
  }

  public function signin(){

    $this->load->view('admin/user/signin',["fail" => $this->input->get("fail")]);

  }

  public function signing(){

    $acc = $this->input->post("account");
    $pwd = $this->input->post("pwd");

    $this->load->database();
    $this->load->model("accountModel");

    $user = $this->accountModel->login($acc,$pwd);

    if($user == null){
      return redirect("admin/user/signin?fail=1");
    }

    $_SESSION["user"] = $user;

    return redirect("admin/light/index");

  }
}
