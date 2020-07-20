<?php

class Model {
  protected $table = '';
  protected $db;
  protected $ID = 'ID';
  
  public function __construct( $table, $ID = 'ID' ) {
    global $db;
    $this->table = $table;
    $this->ID = $ID;
    $this->db = $db;
  }

  public function find( $id ) {
    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$this->table} WHERE {$this->ID} = {$id}", OBJECT);
    if(isset($result[0])) {
      foreach($result[0] as $key => $value) {
        $this->$key = $value;
      }
    }
  }

  public function where( $where = '1=1' ) {
    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$this->table} WHERE {$where}", OBJECT);
    $output = [];
    foreach($result as $i => $res){
      $tmp = new Model($this->table, $this->ID);
      foreach($result[$i] as $key => $value) {
        $tmp->$key = $value;
        if($i==0) {
          $this->$key = $value;
        }
      }
      $output[] = $tmp;
    }
    return $output;
  }

  public function insert($data) {
    global $wpdb;
    //echo "Base Insert : ";
    //$wpdb->show_errors();
    $wpdb->insert($wpdb->prefix . $this->table, $data);
    //echo "\n";
    //$wpdb->print_error();
    //echo "\n-----------\n"
    return $wpdb->insert_id;
  }

  public function update($data, $id = null) {
    global $wpdb;
    if(isset($this->{$this->ID}) && $id==null) {
      $id = $this->{$this->ID};
    }
    // var_dump($id);
    // echo "Old Update<br/>\n";
    if($id==null) {
      return null;
    }
    // echo "UPDATE " . $wpdb->prefix . $this->table . " , id = {$id}";
    if($wpdb->update($wpdb->prefix . $this->table, $data, [$this->ID=>$id])!==false){
      // echo " OK<br/>";
      return true;
    }
    // echo "NOK<br/>";
    return null;
  }

  public function delete($id = null) {
    global $wpdb;
    if(isset($this->{$this->ID}) && $id==null) {
      $id = $this->{$this->ID};
    }
    if($id==null) {
      return null;
    }
    if($wpdb->delete($wpdb->prefix . $this->table, [$this->ID=>$id])!==false){
      return true;
    }
    return null;
  }

  public function query($sql, $echo=false) {
    global $wpdb;
    $sql = str_replace('#PRE#', $wpdb->prefix, $sql);
    if($echo===true) {
      echo $sql;
    }
    return $wpdb->get_results($sql, OBJECT);
  }
}
