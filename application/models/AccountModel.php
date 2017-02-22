<?php

class AccountModel extends CI_Model {
  var $_table = "account";
  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

  public function login($acc,$pwd){
    $this->db->where("acc",$acc);
    $this->db->where("pwd",$pwd);

    $u = $this->db->get($this->_table);
    return array_first_item($u->result());
  }

  
}