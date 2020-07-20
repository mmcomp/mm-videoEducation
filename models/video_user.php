<?php
class VideoUser extends MyModel {
  public function __construct($id=null) {
    parent::__construct('video_users', $id);
  }

  /*
  public function addUser($readd = false) {
    $current_user = wp_get_current_user();
    $user = $this->where('user_id = ' . $current_user->ID);
    if(isset($user[0]) && !$readd) {
      return $user[0];
    }

    $adobeConnect = new AdobeConnect("saied.banuie@gmail.com", "Banuie@159951");
    $password = uniqid('pass-');
    if($readd && isset($user[0])) {
      $password = $user[0]->password;
    }
    // echo "Adding User To Adobe<br/>";
    $adobeUser = $adobeConnect->createUser($current_user->user_firstname, $current_user->user_lastname,  $password, $current_user->user_email);
    // var_dump($adobeUser);
    // echo "<br/>";
    if($adobeUser['response']['status']['@attributes']['code']=='ok') {
      $principal_id = $adobeUser['response']['principal-list']['principal']['@attributes']['principal-id'];
      $data['user_id'] = $current_user->ID;
      $data['principal_id'] = $principal_id;
      $data['password'] = $password;
      if($readd && isset($user[0])) {
        $id = $user[0]->id;
        parent::update($data);
      }else {
        $id = parent::insert($data);
      }
      $this->find($id);
      return null;
    }
    return null;
  }
  */

  /*
  public function getSession() {
    $current_user = wp_get_current_user();
    // var_dump($current_user->user_email);
    $user = $this->where('user_id = ' . $current_user->ID);
    if($current_user->user_email=='' || $current_user->user_email==null) {
      $args = array(
        'ID'         => $current_user->id,
          'user_email' => esc_attr( $current_user->user_login . "@aref-group.ir" )
      );
      wp_update_user( $args );        
    }

    if(!isset($user[0]) || $user[0]->principal_id==null) {
      $this->addUser(true);
    }

    $adobeConnect = new AdobeConnect($current_user->user_email, $user[0]->password);
    $adobeConnect->login();
    return $adobeConnect->getSession();
  }
  */
}
