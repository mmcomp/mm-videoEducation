<?php
class MyModel extends Model {
  public function __construct($table, $id=null) {
    parent::__construct($table, 'id');
    if($id!=null) {
      $id = (int)$id;
      parent::where("id = {$id} and deleted = 0");
    }
  }

  public function find( $id ) {
    parent::where("id = {$id} and deleted = 0");
  }

  public function where( $where = null ) {
    if($where==null) {
      $where = 'deleted = 0';
    }else {
      $where .= ' and deleted = 0';
    }
    return parent::where($where);
  }

  public function whereWithOrder( $where = null, $order = null ) {
    if($where==null) {
      $where = 'deleted = 0';
    }else {
      $where .= ' and deleted = 0';
    }
    return parent::where($where . (($order!=null)?$order:''));
  }

  public function insert($data) {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = $data['created_at'];
    return parent::insert($data);
  }

  public function update($data, $id = null) {
    // echo "Start UPDATE<br/>\n";
    $data['updated_at'] = date('Y-m-d H:i:s');
    return parent::update($data, $id);
  }

  public function delete($id = null) {
    if(isset($this->id) && $id==null) {
      $id = $this->id;
    }
    if($id==null) {
      return null;
    }
    return $this->update(['deleted'=>1], $id);
  }
}