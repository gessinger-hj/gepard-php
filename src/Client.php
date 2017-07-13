<?php
namespace Gepard;
use \Socket\Raw\Factory as SocketFactory;

class Client {

  private static $counter = 1 ;
  protected $socket;
  protected $host;
  protected $port;
  protected $event_factory;
  protected $hostname ;
  protected $version = 1 ;
  protected $brokerVersion = 0 ;
  protected $_heartbeatIntervalMillis = 30000 ;
  protected $_callbacks = [] ;
  protected $_listener ;
  function __construct($port = 17501, $host = "localhost", SocketFactory $socket_factory = null, EventFactory $event_factory = null) {
    $this->host = $host;
    $this->port = $port;
    $this->_listener = new MultiHash() ;

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

    $client_info                         = new Event ( "system", null, "client_info" ) ;
    $body = $client_info->getBody() ;
    $client_info->setValue ( "language", "PHP" ) ;
    $client_info->setValue ( "hostname", gethostname() ) ;
    $client_info->setValue ( "connectionTime", (new \DateTime())->format(\DateTime::RFC3339) ) ;

    $stack = debug_backtrace();
    $firstFrame = $stack[count($stack) - 1];
    $client_info->setValue ( "application", $firstFrame['file'] ) ;
    $client_info->setValue ( "USERNAME", get_current_user() ) ;
    $client_info->setValue ( "version", $this->version ) ;
    // $client_info->setValue ( "channels", self.channels ) ; // TODO:
    $this->emit ( $client_info ) ;
    $broker_info = $this->event_factory->eventFromJSON($this->readJSONBlock());
    if ( $broker_info->getName() === "system" && $broker_info->getType() === "broker_info" ) {
      $this->brokerVersion = $broker_info->getValue ( "brokerVersion" ) ;
      if ( $this->brokerVersion > 0 ) {
        $this->_heartbeatIntervalMillis = $broker_info->getValue ( "_heartbeatIntervalMillis" ) ;
      }
    }
  }

  public function getUniqueId() {
    $address = "" ;
    $port = -1 ;
    $res = $this->socket->getResource() ;
    if ( ! $res) {
      $uid = $this->port . "_" . time() . "_" . Client::$counter ;
      return $uid ;
    }
    socket_getpeername( $res, $address, $port ) ;
    return gethostname() . "_" . $port . "_" . time() . "_" . Client::$counter ;
  }

  public function setEventFactory(EventFactory $event_factory) {
    $this->event_factory = $event_factory;
  }

  function emit(Event $event) {
    $uid = $this->getUniqueId() ;
    $event->setUniqueId ( $uid ) ;
    $se = $event->toJSON();
    $this->socket->write($se);
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
    if (strlen($buffer) === 0) {
      $e = new \UnexpectedValueException ( "Empty string read from socket." ) ;
      var_dump($e->getTraceAsString());
      throw $e ;
    }
    return $buffer;
  }

  function request($name, array $body = [] ) {
    $ev = new Event($name);
    $ev->setBody($body);
  
    $ev->setResultRequested();
    $this->emit($ev);

    $ev = $this->_listen($name);
    return $ev;
  }

  public function listen() {
    $this->_listen(null);
  }

  private function _listen($name) {
    while(true) {
      $se = $this->readJSONBlock() ;
      $ev = $this->event_factory->eventFromJSON($se);
      if ( $ev->getName() === "system" ) {
        if ( $ev->getType() === "addEventListener" ) {
          if ( $ev->isBad() ) {
            throw new \InvalidArgumentException ( "Could not add event listener" ) ;
          }
          continue ;
        }
        if ( $ev->getType() === "PING" ) {
          $ev->setType ( "PONG" ) ;
          $this->emit ( $ev ) ;
          continue ;
        }
      }
      $ev->_Client = $this ;
      if ($name && $ev->getName() === $name ) {
        return $ev ;
      }
      $l = $this->_listener->get ( $ev->getName() ) ;
      if ( $l ) {
        foreach ($l as $v) {
          $v ( $ev ) ;
        }
      }
      if ( $ev->getType() === "shutdown" ) {
        break ;
      }
      continue ;
    }
  }

  public function on ( $eventNameList, $callback)
  {
    if ( is_string($eventNameList))
    {
      $eventNameList = [ $eventNameList ] ;
    }
    if ( ! $callback )
    {
      throw new \InvalidArgumentException ( "Missing callback for event: '$eventNameList'" ) ;
    }
    if ( ! is_array($eventNameList) ) {
      throw new \InvalidArgumentException ( "$eventNameList must be an array of strings." ) ;
    }
    foreach ($eventNameList as $value) {
      $this->_listener->put ( $value, $callback ) ;
    }
    $e = new Event ( "system", null, "addEventListener" ) ;
    $e->setValue ( "eventNameList", $eventNameList ) ;
    $this->emit ( $e ) ;
  }
  public function sendResult ( $event ) {
    if ( ! $event->isResultRequested() ) {
      echo ( "No result requested:\n" ) ;
      echo ( $event ) ;
      throw new \InvalidArgumentException ( "No result requested" ) ;
    }
    $event->setIsResult() ;
    $this->socket->write($event->toJSON());
  }
}
