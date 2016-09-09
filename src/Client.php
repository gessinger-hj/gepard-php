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
      throw new \Exception("Socket connection to ".$host.":".$port." unsuccessful");
    }

  }

  function emit(Event $event) {
      socket_write($this->socket_handle, $event->toJSON());
  }

  function request($name, array $body = [], $block = true) {
    $ev = new Event($name);
    $ev->setBody($body);
  
    $ev->setResultRequested();

    $this->emit($ev);

    $ev = $this->listen($block);
    return $ev;
  }

  function listen($block = true) {
    $char = "";
    $buffer = "";

    $flag = $block ? MSG_WAITALL : MSG_DONTWAIT;

    $levels = 0;
    while(true) {
     
      socket_recv($this->socket_handle, $char, 1, $flag);
 
      if($char === "{") {
        $levels++;
      }
      elseif($char === "}") {
        $levels--;
      }

      $buffer = $buffer.$char;

      if($levels === 0) {
        break;
      }

    }

    $ev = Event::fromJSON($buffer);
    return $ev;
  }

}
