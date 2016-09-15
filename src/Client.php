<?php
namespace Gepard;
use \Socket\Raw\Factory as SocketFactory;

class Client {
  
  protected $socket;
  protected $host;
  protected $port;
  protected $event_factory;

  function __construct($port = 17501, $host = "localhost", SocketFactory $socket_factory = null, EventFactory $event_factory = null) {
    $this->host = $host;
    $this->port = $port;

    if($socket_factory === null) {
      $socket_factory = new SocketFactory();
    }

    if($event_factory === null) {
      $this->event_factory = new EventFactory();
    }
    else {
      $this->event_factory = $event_factory;
    }

    $this->socket = $socket_factory->createClient($host.":".$port);

  }

  public function setEventFactory(EventFactory $event_factory) {
    $this->event_factory = $event_factory;
  }

  function emit(Event $event) {
      $this->socket->write($event->toJSON());
  }

  function request($name, array $body = [], $block = true) {
    $ev = new Event($name);
    $ev->setBody($body);
  
    $ev->setResultRequested();

    $this->emit($ev);

    $ev = $this->listen([$name], $block);
    return $ev;
  }

  public function readJSONBlock($block = true) {

    $char = "";
    $buffer = "";

    $flag = $block ? MSG_WAITALL : MSG_DONTWAIT;

    $levels = 0;
    while(true) {
     
      $char = $this->socket->read(1);
 
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

    return $buffer;
  }

  public function listen(array $events = []) {
    while(true) { 
      $ev = $this->event_factory->eventFromJSON($this->readJSONBlock());

      if(in_array($ev->getName(), $events)) {
        break;
      }
    }
    return $ev; 
  }

}
