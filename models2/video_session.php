<?php
class VideoSession extends MyModel {
  public function __construct($id=null) {
    parent::__construct('video_session', $id);
  }

  public function loadItem() {
    if(!isset($this->id)) {
      return null;
    }
    $sql = "SELECT #PRE#video_session.id SESSION_ID, #PRE#video_session.name SESSION_NAME, #PRE#video_session.start_date SESSION_DATE, #PRE#video_session.start_time SESSION_TIME, #PRE#video_session.end_time SESSION_END, #PRE#video_session.item_id CLASS_ID, the_product.post_title CLASS_NAME  FROM  #PRE#video_session  LEFT JOIN #PRE#posts AS the_product ON (the_product.ID=#PRE#video_session.item_id) WHERE  #PRE#video_session.id = {$this->id}";
    $results = $this->query($sql);
    if(isset($results[0])) {
      foreach($results[0] as $key=>$value) {
        $this->$key = $value;
      }
      return $results[0];
    }
    return null;
  }

  public function loadByIds($ids) {
    if(count($ids)==0) {
      return [];
    }
    $ids = implode(',', $ids);
    return $this->where("id in ({$ids})");
  }

  public function loadByItem($item_id, $currents = false, $advance = false) {
    if($currents===false) {
      return $this->where("item_id = {$item_id}");
    }
    $today = date("Y-m-d");
    $results = $this->where("item_id = {$item_id} and start_date>='{$today}'");
    $passed = false;
    if(count($results)==0) {
      $results = $this->where("item_id = {$item_id}");
      $passed = true;
    }
    if(isset($results[count($results)-1])) {
      foreach($results[count($results)-1] as $key=>$value) {
        $this->$key = $value;
      }
    }
    if($advance!==true) {
      return $results;
    }

    return [
      "passed"=>$passed,
      "results"=>$results,
    ];
  }

  public function loadByItemIds($item_id) {
    $data = $this->loadByItem($item_id);
    $out = [];
    foreach($data as $dt) {
      $out[] = $dt->id;
    }
    return $out;
  }

  public function sumPrice($ids) {
    if(!isset($ids[0])) {
      return 0;
    }
    $sql = "SELECT SUM(price) AS pr FROM #PRE#video_session WHERE id IN (" . implode(',', $ids) . ") AND deleted = 0";
    $result = $this->query($sql);
    return (int)$result[0]->pr;
  }

  public function idToNames($ids) {
    if(!isset($ids[0])) {
      return '';
    }
    $sql = "SELECT name FROM #PRE#video_session WHERE id IN (" . implode(',', $ids) . ") AND deleted = 0";
    $result = $this->query($sql);
    $names = [];
    foreach($result as $res) {
      $names[] = $res->name;
    }
    return $names;
  }

  public function loadClasses($filters = []) {
    $customer_orders = get_posts(array(
      'numberposts' => -1,
      'meta_key' => '_customer_user',
      'orderby' => 'date',
      'order' => 'DESC',
      'meta_value' => get_current_user_id(),
      'post_type' => wc_get_order_types(),
      'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing'),
    ));

    $orders = [];
    foreach ($customer_orders as $customer_order) {
      $sql = "SELECT meta_key, meta_value FROM #PRE#woocommerce_order_itemmeta LEFT JOIN #PRE#woocommerce_order_items ON (#PRE#woocommerce_order_items.order_item_id=#PRE#woocommerce_order_itemmeta.order_item_id) WHERE order_id = {$customer_order->ID}";
      $orderItems = $this->query($sql);
      $video_sessions = '';
      $product_id = 0;
      foreach($orderItems as $orderItem) {
        if($orderItem->meta_key=='video_sessions') {
          $video_sessions = trim($orderItem->meta_value);
        }else if($orderItem->meta_key=='_product_id') {
          $product_id = (int)$orderItem->meta_value;
        }
      }
      if($product_id>0) {
        $orders[] = $product_id;
      }
    }
    $sql = "SELECT #PRE#posts.ID as id, post_title, post_content, post_name, (SELECT SUM(price) FROM #PRE#video_session where item_id=#PRE#posts.ID) as price FROM #PRE#posts LEFT JOIN #PRE#postmeta on (post_id=#PRE#posts.ID) WHERE meta_key='_is_video' AND meta_value='yes' AND #PRE#posts.post_status='publish' #FILTERS# GROUP BY #PRE#posts.id";
    if(count($filters)>0) {
      $sql = str_replace('#FILTERS#', 'AND #PRE#posts.ID in (' . implode(',', $filters) . ')', $sql);
    }else {
      $sql = str_replace('#FILTERS#', '', $sql);
    }
    $results = $this->query($sql);
    $sql = "SELECT * FROM #PRE#term_relationships WHERE object_id = #id#";
    foreach($results as $i => $result) {
      $cats = $this->query(str_replace('#id#', $result->id, $sql));
      $theCats = [];
      foreach($cats as $cat) {
        $theCats[] = $cat->term_taxonomy_id;
      }
      $product   = wc_get_product( $result->id );
      $image_id  = $product->get_image_id();
      $results[$i]->image_url = wp_get_attachment_image_url( $image_id, [255, 255]);//'full' );
      $results[$i]->cats = $theCats;
      $results[$i]->mine = in_array($result->id, $orders);
    }
    return $results;
  }

  public function loadCatalgories($category_id = 0) {
    $sql = "SELECT #PRE#terms.* FROM #PRE#term_taxonomy left join #PRE#terms on (#PRE#term_taxonomy.term_id=#PRE#terms.term_id) WHERE taxonomy = 'product_cat' and parent={$category_id}";
    return $this->query($sql);
  }

  public function loadMyClassSessions() {
    $customer_orders = get_posts(array(
      'numberposts' => -1,
      'meta_key' => '_customer_user',
      'orderby' => 'date',
      'order' => 'DESC',
      'meta_value' => get_current_user_id(),
      'post_type' => wc_get_order_types(),
      'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing'),
    ));

    $out = [];
    foreach ($customer_orders as $customer_order) {
      $sql = "SELECT meta_key, meta_value FROM #PRE#woocommerce_order_itemmeta LEFT JOIN #PRE#woocommerce_order_items ON (#PRE#woocommerce_order_items.order_item_id=#PRE#woocommerce_order_itemmeta.order_item_id) WHERE order_id = {$customer_order->ID}";
      $orderItems = $this->query($sql);
      $video_sessions = '';
      $product_id = 0;
      foreach($orderItems as $orderItem) {
        if($orderItem->meta_key=='video_sessions') {
          $video_sessions = trim($orderItem->meta_value);
        }else if($orderItem->meta_key=='_product_id') {
          $product_id = (int)$orderItem->meta_value;
        }
      }

      $out[$product_id] = explode(',', $video_sessions);
    }

    return $out;
  }

  public function loadMineClasses($names=false) {
    $id = get_current_user_id();
    $customer = wp_get_current_user();

    $customer_orders = get_posts(array(
        'numberposts' => -1,
        'meta_key' => '_customer_user',
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_value' => get_current_user_id(),
        'post_type' => wc_get_order_types(),
        'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing'),
    ));

    $orders = [];
    $filters = [];
    $sessions = [];
    foreach ($customer_orders as $customer_order) {
      $sql = "SELECT meta_key, meta_value FROM #PRE#woocommerce_order_itemmeta LEFT JOIN #PRE#woocommerce_order_items ON (#PRE#woocommerce_order_items.order_item_id=#PRE#woocommerce_order_itemmeta.order_item_id) WHERE order_id = {$customer_order->ID}";
      $orderItems = $this->query($sql);
      $video_sessions = '';
      $product_id = 0;
      foreach($orderItems as $orderItem) {
        if($orderItem->meta_key=='video_sessions') {
          $video_sessions = trim($orderItem->meta_value);
        }else if($orderItem->meta_key=='_product_id') {
          $product_id = (int)$orderItem->meta_value;
        }
      }
      if($product_id>0/* && $video_sessions!=''*/) {
        $sql = "SELECT post_title FROM #PRE#posts WHERE ID = {$product_id}";
        $product = $this->query($sql);
        if($video_sessions!='') {
          $sql = "SELECT * FROM #PRE#video_session WHERE id IN ({$video_sessions})";
          $vSessions = $this->query($sql);
        }
        if(isset($product[0])/* && isset($vSessions[0])*/) {
          $filters[] = $product_id; // $customer_order->ID;
          if($names===false) {
            $sessions[$product_id/*$customer_order->ID*/] = explode(',' ,$video_sessions);
          } else {
            $tmpSession = [];
            $recenetSessions = [];
            if(isset($vSessions) && isset($vSessions[0])) {
              foreach($vSessions as $vSession) {
                $tmpSession[] = [
                  "id" => $vSession->id,
                  "name" => $vSession->name,
                  "start_date"=>jdate("l j F Y", strtotime($vSession->start_date)),
                  "start_time"=>date("H:i", strtotime($vSession->start_time)),
                  "session_date"=>$vSession->start_date,
                ];
              }
            }else {
              $recenetSessions = $this->loadByItem($product_id, true);
            }
            $sessions[$product_id/*$customer_order->ID*/] = [
              "name"=>$product[0]->post_title,
              "sessions"=>$tmpSession,
              "recent_sessions"=>$recenetSessions,
            ];
          }
        }
      }
    }

    return [
      "classes"=>((count($filters)>0)?$this->loadClasses($filters):[]),
      "sessions"=>$sessions,
    ];
  }

  public function insert($data) {
    // echo "start adobe\n";
    $adobeConnect = new AdobeConnect("saied.banuie@gmail.com", "Banuie@159951");
    $sessionPath = uniqid('aref-');
    $data['name'] = str_replace('ی', 'ي', $data['name']);
    // echo "create meetinig: " . $data['name'] . " " . $sessionPath . "\n";
    $sco_id = $adobeConnect->createMeeting($data['name'], $sessionPath);
    // var_dump($sco_id);
    if(isset($sco_id['sco-id'])) {
      $sco_id = $sco_id['sco-id'];
    }else {
      $sco_id = null;
    }
    $data['sco_id'] = $sco_id;
    $data['adobe_path'] = $sessionPath;
    $out =  parent::insert($data);
    //var_dump($out);
    return $out;
  }
}
