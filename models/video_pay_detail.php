<?php
class VideoPayDetail extends MyModel {
  public function __construct($id=null) {
    parent::__construct('video_pay_details', $id);
  }
  
  public function loadByOrder($order_id) {
    $results = $this->where("order_id = {$order_id}");
    if(isset($results[0])) {
      foreach($results[0] as $key=>$value) {
        $this->$key = $value;
      }
    }
    return $results;
  }

  public function loadByProductAndUser($product_id, $user_id){
    $results = $this->where("product_id = {$product_id} AND user_id = {$user_id} ");
    if(!isset($results[0])){
      foreach($results[0] as $key=>$value) {
        $this->$key = $value;
      }
    }
  }

  public function insertIfNot($data){
    $results = $this->where("product_id = {$data['product_id']} AND user_id = {$data['user_id']} ");
    if(!isset($results[0])){
      $this->insert($data);
    }else{
      $this->update($data, $results[0]->id);
    }
  }
}
