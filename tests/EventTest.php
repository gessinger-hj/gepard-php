<?php
namespace Gepard;
use Gepard\Event;
use phpmock\phpunit\PHPMock;
use phpmock\MockBuilder;

class EventTest extends \PHPUnit_Framework_TestCase {

  use PHPMock;

  public function testConstruct() {
    $ev = new Event("EVENTNAME", "EVENTTYPE", ["body"]); 
  }

  public function testToJson() {
    $ev = new Event("EVENTNAME", "EVENTTYPE", ["body"]); 
    $dt = $ev->control["createdAt"];
    $ct = $dt->format(\DateTime::ISO8601);

    $exp = '{"className":"Event","name":"EVENTNAME","type":"EVENTTYPE","control":{"createdAt":"'.$ct.'","plang":"PHP"},"body":{}}';
    $act = json_encode($ev);
    $this->assertEquals($exp, $act);
    $this->assertEquals($exp, (string)$ev);
    $this->assertEquals($exp, $ev->__toString());
    $this->assertEquals($exp, $ev->toJSON());
  }

  public function testFromJson() {
    $dt = new \DateTime();
    $ct = $dt->format(\DateTime::ISO8601);
    $exp = '{"className":"Event","name":"EVENTNAME","type":"EVENTTYPE","control":{"createdAt":"'.$ct.'","plang":"PHP"},"body":{}}';
    $ev = Event::fromJSON($exp);
    $this->assertEquals($exp, json_encode($ev));  
  }

  

}
