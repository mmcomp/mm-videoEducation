<?php
class VideoSession extends MyModel {
  public function sessionCount($item_id){
    $results = $this->where("item_id = {$item_id}");
    return count($results);
  }

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
    $results = $this->where("item_id = {$item_id} and start_date>='{$today}'", " start_date ");
    $passed = false;
    if(count($results)==0) {
      $results = $this->where("item_id = {$item_id}");
      $passed = true;
    }

    if(isset($results[0])) {
      foreach($results[0] as $key=>$value) {
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
  public function sumPrice($ids, $item_id) {
    //var_dump($ids);
    //var_dump($item_id);
    //die();
    $sql = "SELECT SUM(price) AS pr FROM #PRE#video_session WHERE item_id = " . $item_id . " AND deleted = 0";
    $result = $this->query($sql);
    $globalSum =  (int)$result[0]->pr;

    if(!isset($ids[0])) {
      return $globalSum;
    }
    
    $video_sessions = [];
    foreach($ids as $t) {
        if((int)$t>0) {
            $video_sessions[] = $t;
        }
    }
    $ids = $video_sessions;
    // if(count($video_sessions)>0) {
    //   $ids = implode(',', $video_sessions);
    // }
    // var_dump($ids);
    // var_dump($video_sessions);
    // die();
    // var_dump($ids);
    $currentSum = $globalSum;
    if(count($ids)>0) {
      $sql = "SELECT SUM(price) AS pr, item_id FROM #PRE#video_session WHERE id IN (" . implode(',', $ids) . ") AND deleted = 0";
      $result = $this->query($sql);
      $currentSum =  (int)$result[0]->pr;
    }
    // $item_id = $result[0]->item_id;


    // echo 'current:' . $currentSum . ', global:' . $globalSum;
    // die();
    if($currentSum==$globalSum) {
      $product = wc_get_product($item_id);
      return (int)$product->price;
    }

    return $currentSum;
  }
  // public function sumPrice($ids) {
  //   if(!isset($ids[0])) {
  //     return 0;
  //   }
  //   //--remove empty values from array ---- 
  //   for($i=0;$i<count($ids);$i++){
  //     if($ids[$i]=="") unset($ids[$i]);
  //   }
  //   $sql = "SELECT SUM(price) AS pr, item_id FROM #PRE#video_session WHERE id IN (" . implode(',', $ids) . ") AND deleted = 0";
  //   $result = $this->query($sql);
  //   $currentSum =  (int)$result[0]->pr;

  //   $item_id = $result[0]->item_id;

  //   $sql = "SELECT SUM(price) AS pr FROM #PRE#video_session WHERE item_id = " . $item_id . " AND deleted = 0";
  //   $result = $this->query($sql);
  //   $globalSum =  (int)$result[0]->pr;

  //   // echo 'current:' . $currentSum . ', global:' . $globalSum;
  //   // die();
  //   if($currentSum==$globalSum) {
  //     $product = wc_get_product($item_id);
  //     return (int)$product->price;
  //   }

  //   return $currentSum;
  // }

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
    $sql = "SELECT #PRE#posts.ID as id, post_title, post_content, post_name/*, (SELECT SUM(price) FROM #PRE#video_session where item_id=#PRE#posts.ID) as price*/ FROM #PRE#posts LEFT JOIN #PRE#postmeta on (post_id=#PRE#posts.ID) WHERE meta_key='_is_video' AND meta_value='yes' AND #PRE#posts.post_status='publish' #FILTERS# GROUP BY #PRE#posts.id";
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
      $results[$i]->price = $product->price;
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

    // var_dump($customer_orders);

    $orders = [];
    $filters = [];
    $sessions = [];
    foreach ($customer_orders as $customer_order) {
      $order=wc_get_order($customer_order->ID);
      // echo "<hr/>\nOrder : " . $customer_order->ID . "<hr/>\n";
      foreach( $order->get_items() as $item ){
        $video_sessions = '';
        $item_data = $item->get_data();
        $product_id = $item_data["product_id"];
        $product_name = $item_data["name"];
        // echo $product_name . "<br/>\n";
        if(get_post_meta($product_id, '_is_video', true)==='yes') {
          // get order item data (in an unprotected array)
          // if(isset($item_data['video_sessions'])) {
          //   $video_sessions = $item_data['video_sessions'];
          // }
          // echo $video_sessions . "<br/>\n";
          // var_dump($item_data);
          // echo "<br/>";
          // get order item meta data (in an unprotected array)
          $item_meta_data = $item->get_meta_data();
          // var_dump($item_meta_data);
          // if(isset($item_meta_data[0])){
          //   echo $item_meta_data[0]->key . '=' . $item_meta_data[0]->value;
          // }
          // echo "<br/>\n";
          foreach($item_meta_data as $metaData) {
            if($metaData->key=='video_sessions') {
              $video_sessions = $metaData->value;
            }
          }
          // get only additional meta data (formatted in an unprotected array)
          // $formatted_meta_data = $item->get_formatted_meta_data();
      
          // Display the raw outputs (for testing)
          // echo '<pre>'; print_r($item_meta_data); echo '</pre>';
          // echo '<pre>'; print_r($formatted_meta_data); echo '</pre>';

        }else {
          $product_id = 0;
        }
        if($product_id>0) {
          // $sql = "SELECT post_title FROM #PRE#posts WHERE ID = {$product_id}";
          // $product = $this->query($sql);
          // echo $video_sessions . "<br/>\n";

          $vSessions = [];
          if($video_sessions!='') {
            $sql = "SELECT * FROM #PRE#video_session WHERE id IN ({$video_sessions}) AND deleted = 0";
            $vSessions = $this->query($sql);
          }

          // var_dump($vSessions);
          // echo "<hr/>\n";

          $filters[] = $product_id; 

          if($names===false) {
            if(!isset($sessions[$product_id])) {
              $sessions[$product_id] = [];
            }
            $sessions[$product_id] = array_merge($sessions[$product_id], explode(',' ,$video_sessions));
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

            if(!isset($sessions[$product_id])) {
              $sessions[$product_id] = [
                "name"=>$product_name,
                "sessions"=>$tmpSession,
                "recent_sessions"=>$recenetSessions,
              ];
            }else {
              if(isset($sessions[$product_id]["sessions"][0])) {
                // Not Full class
                if(isset($vSessions[0])) {
                  $sessions[$product_id]["sessions"] = array_merge($sessions[$product_id]["sessions"], $tmpSession);
                  $sessions[$product_id]["recent_sessions"] = [];
                }else {
                  $sessions[$product_id]["sessions"] = [];
                  $sessions[$product_id]["recent_sessions"] = array_merge($sessions[$product_id]["recent_sessions"], $recenetSessions);
                }
              }

              
            }
          }
        }
      }

      
      // $sql = "SELECT meta_key, meta_value FROM #PRE#woocommerce_order_itemmeta LEFT JOIN #PRE#woocommerce_order_items ON (#PRE#woocommerce_order_items.order_item_id=#PRE#woocommerce_order_itemmeta.order_item_id) WHERE order_id = {$customer_order->ID}";
      // $orderItems = $this->query($sql, false);

      // foreach($orderItems as $orderItem) {
      //   if($orderItem->meta_key=='video_sessions') {
      //     $video_sessions = trim($orderItem->meta_value);
      //   }else if($orderItem->meta_key=='_product_id') {
      //     $product_id = (int)$orderItem->meta_value;
      //   }
      // }

      // if($product_id>0) {
      //   $sql = "SELECT post_title FROM #PRE#posts WHERE ID = {$product_id}";
      //   $product = $this->query($sql);

      //   $vSessions = [];
      //   if($video_sessions!='') {
      //     $sql = "SELECT * FROM #PRE#video_session WHERE id IN ({$video_sessions}) AND deleted = 0";
      //     $vSessions = $this->query($sql);
      //   }


      //   if(isset($product[0])) {
      //     $filters[] = $product_id; 

      //     if($names===false) {
      //       if(!isset($sessions[$product_id])) {
      //         $sessions[$product_id] = [];
      //       }
      //       $sessions[$product_id] = array_merge($sessions[$product_id], explode(',' ,$video_sessions));
      //     } else {
      //       $tmpSession = [];
      //       $recenetSessions = [];
      //       if(isset($vSessions) && isset($vSessions[0])) {
      //         foreach($vSessions as $vSession) {
      //           $tmpSession[] = [
      //             "id" => $vSession->id,
      //             "name" => $vSession->name,
      //             "start_date"=>jdate("l j F Y", strtotime($vSession->start_date)),
      //             "start_time"=>date("H:i", strtotime($vSession->start_time)),
      //             "session_date"=>$vSession->start_date,
      //           ];
      //         }
      //       }else {
      //         $recenetSessions = $this->loadByItem($product_id, true);
      //       }

      //       if(!isset($sessions[$product_id])) {
      //         $sessions[$product_id] = [
      //           "name"=>$product[0]->post_title,
      //           "sessions"=>$tmpSession,
      //           "recent_sessions"=>$recenetSessions,
      //         ];
      //       }else {
      //         if(isset($sessions[$product_id]["sessions"][0])) {
      //           // Not Full class
      //           if(isset($vSessions[0])) {
      //             $sessions[$product_id]["sessions"] = array_merge($sessions[$product_id]["sessions"], $tmpSession);
      //             $sessions[$product_id]["recent_sessions"] = [];
      //           }else {
      //             $sessions[$product_id]["sessions"] = [];
      //             $sessions[$product_id]["recent_sessions"] = array_merge($sessions[$product_id]["recent_sessions"], $recenetSessions);
      //           }
      //         }

              
      //       }
      //     }
      //   }
      // }
      
    }


    return [
      "classes"=>((count($filters)>0)?$this->loadClasses($filters):[]),
      "sessions"=>$sessions,
    ];
  }

  public function insert($data) {
    // $adobeConnect = new AdobeConnect("saied.banuie@gmail.com", "Banuie@159951");
    // $sessionPath = uniqid('aref-');
    $data['name'] = str_replace('ی', 'ي', $data['name']);
    // $sco_id = $adobeConnect->createMeeting($data['name'], $sessionPath);
    // if(isset($sco_id['sco-id'])) {
      // $sco_id = $sco_id['sco-id'];
    // }else {
      // $sco_id = null;
    // }
    // $data['sco_id'] = $sco_id;
    // $data['adobe_path'] = $sessionPath;
    return parent::insert($data);
  }

  public function clear($item_id) {
    $sql = "update #PRE#video_session set deleted = 1 where item_id = $item_id";
    return $this->query($sql);
  }


  public function loadMySessions() {
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

      if($video_sessions=='') {
        $ids = $this->where("item_id = {$product_id}");
        $video_sessions = [];
        foreach($ids as $theId) {
          $video_sessions[] = $theId->id;
        }
        $out = array_merge($out, $video_sessions);
      }else {
        $out = array_merge($out, explode(',', $video_sessions));
      }

    }

    return $out;
  }

  public function loadLiveSessions() {
    $res = $this->query("SELECT * FROM #PRE#video_session WHERE date(start_date) = '" . date("Y-m-d") . "' AND deleted = 0 ORDER BY start_time");

    $mySessions = $this->loadMySessions();
    $out = [];
    foreach($res as $r) {
      $r->mine = false;
      if(in_array($r->id, $mySessions)) {
        $r->mine = true;
      }
      if(get_post_status($r->item_id)=="publish") {
        $out[] = $r;
      }
    }
    return $out;
  }
}
