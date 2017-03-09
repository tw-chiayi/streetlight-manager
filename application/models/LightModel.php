<?php

class LightModel extends CI_Model {
  var $_table = "streetlights";
  var $_table_light_report = "light_report";
  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

  public function insert($light){
    // ["鹿東", "後寮", "鹿草", "豊稠", "西井", "重寮", "施家", "下潭", "光潭", "碧潭", "竹山", "松竹", "三角", "後堀", "下麻"]
    if($this->check_light_exist((object)($light))){
      return false;
    }

    if($this->check_light_name_exist((object)($light))){
      echo "light ".$light["name"]." not exist and name exist <br />";
    }else{
      echo "light ".$light["name"]." not exist <br />";
    }
    // return true;
    // die(var_dump($light));
    $this->db->insert($this->_table,$light);
  }

  public function check_light_name_exist($light){
    $this->db->select("count(*) as cnt");

    if(is_array($light->lat)){
      die(var_dump($light));
    }
    $this->db->where("name","".$light->name);
    $q = $this->db->get($this->_table);

    return array_first_item($q->result())->cnt != 0;
  }

  public function check_light_exist($light){
    $this->db->select("count(*) as cnt");

    if(is_array($light->lat)){
      die(var_dump($light));
    }
    $this->db->where("lat","".$light->lat);
    $this->db->where("lng","".$light->lng);
    $q = $this->db->get($this->_table);

    return array_first_item($q->result())->cnt != 0;
  }

  public function get_all(){
    return $this->db->get($this->_table)->result();
  }


  public function get_all_for_map(){

    $this->db->select("l.status,l.lat,l.lng,l.id,l.name,t.city,t.name as town_name,l.town_id");
    $this->db->join("town t","l.town_id = t.id");

    return $this->db->get($this->_table." l")->result();
  }

  public function get($id){

    $this->db->select("l.lat,l.lng,l.height,l.id,l.name,t.city,t.name as town_name,l.town_id");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("l.id",$id);

    return array_first_item($this->db->get($this->_table." l")->result());
  }

  public function get_repair_light_by_city($city){

    $this->db->select("l.status,l.lat,l.lng,l.height,l.id,l.name,t.city,t.name as town_name,l.town_id,l.mtime");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("t.city",$city);
    $this->db->where("status","1");
    return ($this->db->get($this->_table." l")->result());
  }

  public function get_repair_light_by_ids_city($city,$ids){

    $this->db->select("l.status,l.lat,l.lng,l.height,l.id,l.name,t.city,t.name as town_name,l.town_id,l.mtime");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("t.city",$city);
    $this->db->where_in("l.id",$ids);
    $this->db->where("status","1");
    return ($this->db->get($this->_table." l")->result());
  }

  public function fix_light_by_ids($city,$ids){

    $this->db->set("status","0");
    $this->db->where_in("id",$ids);
    $this->db->where("status","1");
    $this->db->update($this->_table);
  }

  public function insert_report($data){
    $this->db->insert($this->_table_light_report,$data);
    return $this->db->insert_id();
  }

  public function get_city_counts($city){
    $this->db->select("l.status,count(l.*)");

    $this->db->join("town t","l.town_id = t.id");
    $this->db->group_by("l.status");
    $this->db->where("t.city",$city);
    return $this->db->get($this->_table." l")->result();

  }


  public function get_city_reports($city){
    $this->db->select("l.name as light_name,l.status as light_status,r.*");
    
    $this->db->join($this->_table." l"," l.id = r.light_id");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("t.city",$city);
    $this->db->where("r.ctime > (current_date - interval '90 days') ");    
    $this->db->order_by("r.ctime desc");
    $q = $this->db->get($this->_table_light_report." r");


    return $q->result();
  }

  public function get_unhandled_city_reports($city){
    $this->db->select("l.name as light_name,l.status as light_status,r.*");
    
    $this->db->join($this->_table." l"," l.id = r.light_id");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("t.city",$city);
    $this->db->where("r.status",0);
    $this->db->order_by("r.ctime desc");
    $q = $this->db->get($this->_table_light_report." r");


    return $q->result();
  }


  public function set_report_status($report_id,$status){
    $this->db->where("id",$report_id);
    $q = array_first_item($this->db->get($this->_table_light_report)->result());

    if($q == null){
      return null;
    }


    if($status == "1"){
      $this->db->set("status",1);
      $this->db->set("mtime","now()",false);
      $this->db->where("status",0);
      $this->db->where("light_id",$q->light_id);
      $this->db->update($this->_table_light_report);

      $this->db->set("status",1);
      $this->db->set("mtime","now()",false);
      $this->db->where("id",$q->light_id);
      $this->db->update($this->_table);

    }else if($status == "0"){
      $this->db->set("status",2);
      $this->db->set("mtime","now()",false);
      $this->db->where("status",0);
      $this->db->where("light_id",$q->light_id);
      $this->db->update($this->_table_light_report);
    }

  }

  
}