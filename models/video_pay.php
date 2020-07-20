<?php
class VideoPay extends MyModel {
  public function __construct($id=null) {
    parent::__construct('video_pay', $id);
  }

  public function loadByItem($item_id) {
    $results = $this->where("product_id = {$item_id}");
    if(isset($results[0])) {
      foreach($results[0] as $key=>$value) {
        $this->$key = $value;
      }
    }
  }
}
