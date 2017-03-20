<?php
namespace Gepard;

class MockSocket {
  protected $data;
  protected $pos = 0;

  public function __construct($data) {
    if ( !$data OR !strpos ( $data, "body" ) )
    {
      $dt = new \DateTime();
      $ct = $dt->format(\DateTime::ISO8601);
      // $ct = json_encode($dt);
      $json = '{"className":"Event","name":"EVENTNAME","type":"EVENTTYPE","control":{"createdAt":"'.$ct.'","plang":"PHP"},"body":';
      if (!$data) {
        $json .= '{}';
      }
      else {
        $json .= $data ;
      }
      $json .= '}';
      $this->data = $json;
    }
    else {
      $this->data = $data;
    }
  }

  public function read($length) {
    $chunk = substr($this->data, $this->pos, $length);
    $this->pos += $length;
    return $chunk;
  }

  public function write($str) {
  }

  public function getResource() {
    return "";
  }
}
