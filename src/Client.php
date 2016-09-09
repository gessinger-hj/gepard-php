<?php
namespace Gepard;

class Client {
  
  protected $socket_handle;
  protected $host;
  protected $port;

  function __construct($port = 17501, $host = "localhost") {
    echo "connecting to socket at ".$host.":".$port.PHP_EOL; 

    $this->host = $host;
    $this->port = $port;

    $this->socket_handle = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if(!socket_connect($this->socket_handle, $host, $port)) {
      throw new \Exception("Socket connetion to ".$host.":".$port." unsuccessful");
    }

    //$dummy = '{"className":"Event","name":"ALARM","type":"TEST","user":{"className":"User","id":"smith","key":4711,"_pwd":"secret","rights":{"CAN_READ_FILES":"*.docx"},"groups":{},"attributes":{}},"control":{"createdAt":{"type":"Date","value":"2016-09-09T08:12:46.096Z"},"plang":"JavaScript","hostname":"Neuromancer.fritz.box"},"body":{"binaryData":{"type":"Buffer","data":[65,66,67,68,69]}}}';

    $dummy = '{"className":"Event","name":"getFileList","type":"","user":{"className":"User","id":"Paul","rights":{},"groups":{},"attributes":{}},"control":{"createdAt":{"type":"Date","value":"2016-09-09T08:45:27.132Z"},"plang":"JavaScript","hostname":"Neuromancer.fritz.box","_isResultRequested":true,"isInUse":true},"body":{}}';

    socket_write($this->socket_handle, $dummy);

  }

  function listen($event_name, $block = true) {
    $char = "";
    $buffer = "";

    $flag = $block ? MSG_WAITALL : MSG_DONTWAIT;

    $levels = 0;
    while(true) {
     
      socket_recv($this->socket_handle, $char, 1, $flag);

      //echo "CHAR: ".$char.PHP_EOL;
      //echo "LEVEL: ".$levels.PHP_EOL;
        
      if($char === "{") {
        //echo "ONE DOWN".PHP_EOL;
        $levels++;
      }
      elseif($char === "}") {
        //echo "ONE UP".PHP_EOL;
        $levels--;
      }

      $buffer = $buffer.$char;

      if($levels === 0) {
        break;
      }

    }

    return $buffer;
  }

}
