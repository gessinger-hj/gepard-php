<?php
namespace Gepard;

class MockSocket {
  protected $data;
  protected $pos = 0;

  public function __construct($data) {
    $this->data = $data;
  }

  public function read($length) {
    $chunk = substr($this->data, $this->pos, $length);
    $this->pos += $length;
    return $chunk;
  }

  public function write($str) {
    // lalala
  }
}
