<?php 
namespace Gepard;
// composer require hampel/json

use Hampel\Json\Json;
use Hampel\Json\JsonException;

use Gepard\JSAcc ;

class Event implements \JsonSerializable {

  protected $name;
  protected $type;
  public $control = [];
  private $_control ;
  protected $body = [];
  private $_body ;
  const CLASSNAME = "Event";

  static function fromJSON($json) {
    $data = Json::decode($json, true);
    $obj = new Event($data["name"], null, $data["type"]);
    $obj->setBody($data["body"]);
    $obj->control = $data["control"];
    if ( is_string ( $obj->control["createdAt"] ) ) {
      $obj->control["createdAt"] = \DateTime::createFromFormat(\DateTime::ISO8601, $obj->control["createdAt"]);
    }
    return $obj;
  }

  function __construct ( $name, $body=null, $type="" ) {
    if(!is_string($name)) {
      throw new \InvalidArgumentException("name must be a string");
    }
    $this->name = $name;
    $this->type = $type;

    $this->control["createdAt"] = new \DateTime();
    $this->control["plang"] = "PHP";
    if ( ! $body ) {
      $this->body = [] ;
    }
    else {
      $this->body = $body;
    }
    $this->_control = new JSAcc ( $this->control ) ;
    $this->_body = new JSAcc ( $this->body ) ;
  }

  public function setResultRequested($value = true) {
    $this->_control->add ( "_isResultRequested", $value ) ;
  }

  public function isResultRequested() {
    return $this->_control->value ( "_isResultRequested", false ) ;
  }

  public function setName($name) {
    if(!is_string($name)) {
      throw new \InvalidArgumentException("name must be a string");
    }
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  public function setType($type) {
    if(!is_string($type)) {
      throw new \InvalidArgumentException("type must be a string");
    }
    $this->type = $type;
  }

  public function getType() {
    return $this->type;
  }

  public function setBody(array $body) {
    $this->body = $body;
  }

  public function &getBody() {
    return $this->body;
  }

  public function &getControl() {
    return $this->control;
  }

  public function setUniqueId ( $uid ) {
    $id = $this->_control->value ( "uniqueId" ) ;
    if ( ! $id ) {
      $this->_control->add ( "uniqueId", $uid ) ;
    }
  }
  public function __toString() {
      return $this->toJSON();
  }

  public function jsonSerialize() {
    $control = $this->control;
    $createdAt = $control["createdAt"] ;
    if ( is_array ( $createdAt ) ) {
      if ( $createdAt["type"] === "Date" ) {
        $control["createdAt"] = $createdAt["value"] ;
      }
    }
    $body = $this->body;
    if(count($body) === 0) {
      $body = new \stdClass();
    }
    return [
      "className" => self::CLASSNAME,
      "name" => $this->name,
      "type" => $this->type,
      "control" => $control,
      "body" => $body,
    ];
  }

  public function toJSON() {
    return Json::encode($this);
  }

  public function &getValue ( $name ) {
    return $this->_body->value ( $name ) ;
  }

  public function setValue ( $name, $value ) {
    return $this->_body->add ( $name, $value ) ;
  }

  public function isBad()
  {
    $code = $this->getStatusCode() ;
    return $code !== 0 ? true : false ;
  }
  public function &getStatus()
  {
    return $this->_control->value ( "status" ) ;
  }
  public function getStatusReason()
  {
    return $this->_control->value ( "status/reason", "" ) ;
  }
  public function getStatusName()
  {
    return $this->_control->value ( "status/name", "" ) ;
  }
  public function getStatusCode()
  {
    return $this->_control->value ( "status/code", 0 ) ;
  }
  public function setStatus ( $code, $name, $reason )
  {
    if ( ! $code ) {
      $code = 0 ;
    }
    $this->_control->add ( "status/code", $code ) ;
    if ( $name ) $this->_control->add ( "status/name", $name ) ;
    if ( $reason ) $this->_control->add ( "status/reason", $reason ) ;
  }

}
