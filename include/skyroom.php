<?php
class SkyRoom {
    private $apiKey = "apikey-71-819540-0f178abb0c712c4cfd5ae13e4c54687a";
    private $baseUrl = "https://www.skyroom.online/skyroom/api/";
    public function __construct($apiKey = null, $baseUrl = null) {
      if($apiKey!=null && $apiKey!='') {
        $this->apiKey = $apiKey;
      }

      if($baseUrl!=null && $baseUrl!='') {
        $this->baseUrl = $baseUrl;
      }
    }
  
    public function request($action, $method, $params) {
        $ch = curl_init( $this->baseUrl . $this->apiKey );
        $payload = json_encode( array( "action"=> $action, "params"=>$params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        try{
          $result = json_decode($result);
        }catch(Exception $error) {
          $result = null;
        }
        return $result;
    }

    public function createRoom($name, $title, $max_users, $op_login_first = true, $guest_login = false) {
      $room = $this->request("getRoom", "POST", ["name"=>$name]);
      
      if($room['ok']==false){
        return $this->request("createRoom", "POST", [
          "name"=>$name,
          "title"=>$title,
          "max_users"=>$max_users,
          "op_login_first"=>$op_login_first,
          "guest_login"=>$guest_login
        ]);
      }

      return $room;
    }
}