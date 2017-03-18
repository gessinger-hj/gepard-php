<?php
namespace Gepard;

class MockSocket {
  protected $data;
  protected $pos = 0;

  public function __construct($body) {
echo "body=$body\n" ;
if (strpos($body,"event1")>0) {
  $e = new \Exception ;
  var_dump($e->getTraceAsString());
}
    if (!$body OR !strpos ( $body, "className" ) ) {
      $dt = new \DateTime();
      $ct = $dt->format(\DateTime::ISO8601);
      // $ct = json_encode($dt);
      $data = '{"className":"Event","name":"EVENTNAME","type":"EVENTTYPE","control":{"createdAt":"'.$ct.'","plang":"PHP"},"body":';
      if (!$body) {
        $data .= '{}';
      }
      else {
        $data .= $body ;
      }
      $data .= '}';
      $this->data = $data;
    }
    else {
      $this->data = $body;
    }
echo "data=$this->data\n" ;
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
