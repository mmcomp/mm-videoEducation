<?php
class VideoClass extends MyModel {
  public function __construct($id=null) {
    parent::__construct('video_class', $id);
  }
  
  public function loadBySession($session_id) {
    $sql = "SELECT #PRE#video_class.* FROM #PRE#video_class left join #PRE#video_session_class on (#PRE#video_class.id=video_class) WHERE session_id = " . $session_id . " AND #PRE#video_session_class.deleted = 0";
    return $this->query($sql);
  }
}