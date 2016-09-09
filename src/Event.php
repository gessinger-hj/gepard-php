<?php 
namespace Gepard;
// composer require hampel/json

use Hampel\Json\Json;
use Hampel\Json\JsonException;


class Event implements \JsonSerializable {

  protected $name;
  protected $type;
  public $control = [];
  protected $body = [];

  const CLASSNAME = "Event";

  static function fromJSON($json) {
    $data = Json::decode($json, true);
    $obj = new Event($data["name"], $data["type"]);
    $obj->setBody($data["body"]);
    $obj->control = $data["control"];
    $obj->control["createdAt"] = \DateTime::createFromFormat(\DateTime::ISO8601, $obj->control["createdAt"]);
    return $obj;
  }

  function __construct($name, $type = "", $body = []) {
    $this->name = $name;
    $this->type = $type;

    $this->control["createdAt"] = new \DateTime();
    $this->control["plang"] = "PHP";
    $this->body = [];
  }

  public function setResultRequested($value = true) {
    $this->control["_isResultRequested"] = $value;
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

  public function setBody(array $body) {
    $this->body = $body;
  }

  public function getBody() {
    return $this->body;
  }

  public function __toString() {
    return $this->toJSON();
  }

  public function jsonSerialize() {

    $control = $this->control;

    $control["createdAt"] = $control["createdAt"]->format(\DateTime::ISO8601);

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
}
