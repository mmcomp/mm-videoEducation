<?php
class AdobeConnect {
  private $baseUrl = 'http://185.53.140.138/api/xml?'; // 'http://class.aref-group.ir/api/xml?';
  private $login = null;
  private $password = null;
  private $session = null;
  private $retry = 0;
  public function __construct($login, $password, $baseUrl=null) {
    $this->login = $login;
    $this->password = $password;
    if($baseUrl!=null && $baseUrl!='') {
      $this->baseUrl = $baseUrl;
    }
  }

  public function _request($action, $method, $params) {
    $url = "{$this->baseUrl}action={$action}";
    if($params) {
      foreach($params as $key=>$value) {
        $value = urlencode($value);
        $url .= "&{$key}={$value}";
      }
    }

    // echo $url."<br/>\n";
    $xmlstring = file_get_contents($url);
    $xml = simplexml_load_string($xmlstring);
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);
    $cockie = null;
    foreach($http_response_header as $http_response_head) {
      if(strpos($http_response_head, 'Set-Cookie:')===0) {
        $tmp = explode('=', $http_response_head);
        if(isset($tmp[1])) {
          $tmp = explode(';', $tmp[1]);
          $cockie = $tmp[0];
        }
      }
    }

    return [
      "response"=>$array,
      "cockie"=>$cockie,
    ];
  }

  public function request($action, $method, $params) {
    if($params==null) {
      $params = [];
    }
    $out = null;
    if($this->session==null) {
      if(!$this->login()) {
        if($this->retry<5) {
          $this->retry++;
          $this->session = null;
          return $this->request($action, $method, $params);
        }else {
          $this->retry = 0;
          $this->session = null;
          return $out;
        }
      }
    }
    $params["session"] = $this->session;
    $out = $this->_request($action, $method, $params);
    if($out['response']['status']['@attributes']['code']!='ok') {
      if($this->retry<5) {
        $this->retry++;
        $this->session = null;
        return $this->request($action, $method, $params);
      }
    }
    return $out;
  }

  public function login() {
    $out = $this->_request("login", "GET", [
      "login"=>$this->login,
      "password"=>$this->password,
    ]);
    
    if($out['response']['status']['@attributes']['code']=='ok') {
      $this->session = $out['cockie'];
      return true;
    }
    return false;
  }

  // public function getSession() {
  //   return $this->session;
  // }

  public function setSession($session) {
    $this->session = $session;
  }

  public function principalsList($email=null) {
    $filters = null;
    if($email!=null && trim($email)!='') {
      $filters = [
        "filter-email"=>trim($email),
      ];
    }
    return $this->request("principal-list", "GET", $filters);
  }

  public function createUser($first_name, $last_name, $password, $email) {
    $principal = $this->principalsList($email);
    if(isset($principal['response']['principal-list']['principal'])) {
      return $principal;
    }
    $createUser = $this->request("principal-update", "GET", [
      "first-name"=>$first_name,
      "last-name"=>$last_name,
      "login"=>$email,
      "password"=>$password,
      "type"=>"user",
      "send-email"=>"true",
      "has-children"=>"0",
      "email"=>$email,
    ]);
    if($createUser['response']['status']['@attributes']['code']=='ok') {
      return $this->principalsList($email);
    }
    return $createUser;
  }

  public function meetingList() {
    return $this->request("report-bulk-objects", "GET", ["filter-type"=>"meeting"]);
  }

  public function myMeetings() {
    return $this->request("report-my-meetings", "GET", null);//["filter-expired"=>"false"]);
  }

  public function findMeetingFolder() {
    $scos = $this->scoShort();
    $sco_id = null;
    $shortcuts = $scos['response']['shortcuts']['sco'];
    foreach($shortcuts as $shortcut) {
      if($sco_id==null && $shortcut['@attributes']['type']=='my-meetings') {
        $sco_id = $shortcut['@attributes']['sco-id'];
      }
    }
    return $sco_id;
  }

  public function createMeeting($name, $path) {
    $folder_id = $this->findMeetingFolder();
    if($folder_id) {
      $meeting = $this->request("sco-update", "GET", [
        "name"=>$name,
        "folder-id"=>$folder_id,
        "icon"=>"course",
        "type"=>"meeting",
        "url-path"=>$path,
      ]);
      if($meeting['response']['status']['@attributes']['code']=='ok') {
        $course_sco_id = $meeting['response']['sco']['@attributes']['sco-id'];
        $meeting = $this->enrollUser("public-access", $course_sco_id, "denied");
        if($meeting['response']['status']['@attributes']['code']=='ok') {
          $meeting['sco-id'] = $course_sco_id;
        }
        return $meeting;
      }
    }
    return null;
  }

  public function addUserMeeting($user_principal_id, $meeting_sco_id, $permission="view") {
    return $this->request("permissions-update", "GET", [
      "principal-id"=>$user_principal_id,
      "acl-id"=>$meeting_sco_id,
      "permission-id"=>$permission,
    ]);
  }

  public function scoShort() {
    return $this->request("sco-shortcuts", "GET", null);
  }

  public function findCourseFolder() {
    $scos = $this->scoShort();
    $sco_id = null;
    $shortcuts = $scos['response']['shortcuts']['sco'];
    foreach($shortcuts as $shortcut) {
      if($sco_id==null && $shortcut['@attributes']['type']=='courses') {
        $sco_id = $shortcut['@attributes']['sco-id'];
      }
    }
    return $sco_id;
  }

  public function createCourse($name) {
    $folder_id = $this->findCourseFolder();
    if($folder_id) {
      return $this->request("sco-update", "GET", [
        "name"=>$name,
        "folder-id"=>$folder_id,
        "icon"=>"course",
        "type"=>"content",
      ]);
    }
    return null;
  }

  public function enrollUser($user_principal_id, $course_sco_id, $permission_id="view") {
    return $this->request("permissions-update", "GET", [
      "acl-id"=>$course_sco_id,
      "principal-id"=>$user_principal_id,
      "permission-id"=>$permission_id,
    ]);
  }

  public function myTrainings() {
    return $this->request("report-my-training", "GET", null);
  }
}
