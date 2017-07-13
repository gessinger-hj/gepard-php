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
  public $_body ;
  public $_Client ;
  const CLASSNAME = "Event";

  static function fromJSON($json) {
    $data = Json::decode($json, true);
    $name = "" ;
    if (isset($data["name"])) {
      $name = $data["name"] ;
    }
    $type = null ;
    if (isset($data["type"])) {
      $type = $data["type"] ;
    }
    $obj = new Event($name, null, $type);
    $obj->setBody($data["body"]);
    $obj->control = $data["control"];
    if ( isset ( $obj->control["createdAt"] ) ) {
      $createdAt = $obj->control["createdAt"] ;
      if (is_string($createdAt)) {
        $obj->control['createdAt'] = \DateTime::createFromFormat(\DateTime::ATOM, $createdAt);
        if ( ! $obj->control['createdAt'] ) {
          $obj->control['createdAt'] = $createdAt ;
        }
      }
      else
      if ( isset ( $createdAt["value"] ) && is_string ( $createdAt["value"] ) ) {
        $obj->control['createdAt'] = \DateTime::createFromFormat(\DateTime::ATOM, $createdAt["value"]);
        if ( ! $obj->control['createdAt'] ) {
          $obj->control['createdAt'] = $createdAt["value"] ;
        }
      }
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

  public function setIsResult() {
    $this->_control->add ( "_isResult", True ) ;
  }

  public function isResult() {
    return $this->_control->value ( "_isResult", false ) ;
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
    ob_start();
    $_control = $this->_control;
    $_body = $this->_body;
    $_Client = $this->_Client;
    $this->_control = null;
    $this->_body = null;
    $this->_Client = null;
    var_dump($this);
    $s = "(Event)\n" . ob_get_clean();
    $this->_body = $_control;
    $this->_body = $_body;
    $this->_Client = $_Client;
    return $s;
  }

  public function jsonSerialize() {
    $control = $this->control;
    if (isset($control["createdAt"])) {
      $createdAt = $control["createdAt"] ;
      if (is_object($createdAt)) {
        if ($createdAt instanceof \DateTime) {
          $control["createdAt"] = $createdAt->format(\DateTime::ATOM);
        }
        else {

        }
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
    $this->_body = null;
    $this->_Client = null;
    $se = Json::encode($this);
    return $se;
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
  public function sendBack() {
    $this->_Client->sendResult ( $this ) ;
    $this->_Client = null ;
  }
}
