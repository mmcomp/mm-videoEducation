<?php
class VideoUser extends MyModel {
  public function __construct($id=null) {
    parent::__construct('video_users', $id);
  }

  public function addUser() {
    $current_user = wp_get_current_user();
    $user = $this->where('user_id = ' . $current_user->ID);
    if(isset($user[0])) {
      return $user[0];
    }
    $adobeConnect = new AdobeConnect("saied.banuie@gmail.com", "Banuie@159951");
    $password = uniqid('pass-');
    $adobeUser = $adobeConnect->createUser($current_user->user_firstname, $current_user->user_lastname,  $password, $current_user->user_email);
    if($adobeUser['response']['status']['@attributes']['code']=='ok') {
      $principal_id = $adobeUser['response']['principal-list']['principal']['@attributes']['principal-id'];
      $data['user_id'] = $current_user->ID;
      $data['principal_id'] = $principal_id;
      $data['password'] = $password;
      $id = parent::insert($data);
      $this->find($id);
      return null;
    }
    return null;
  }
}
