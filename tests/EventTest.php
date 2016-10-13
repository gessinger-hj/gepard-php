<?php
namespace Gepard;
use Gepard\Event;
use phpmock\phpunit\PHPMock;
use phpmock\MockBuilder;

class EventTest extends \PHPUnit_Framework_TestCase {

  use PHPMock;

  public function testConstruct() {
    $ev = new Event("EVENTNAME", ["body"], "EVENTTYPE"); 
  }

  public function testToJson() {
    $ev = new Event("EVENTNAME", ["body"], "EVENTTYPE"); 
    $dt = $ev->control["createdAt"];
    $ct = $dt->format(\DateTime::ISO8601);

    $exp = '{"className":"Event","name":"EVENTNAME","type":"EVENTTYPE","control":{"createdAt":"'.$ct.'","plang":"PHP"},"body":["body"]}';
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

  public function testIsRequltRequested() {
    $name = "TESTNAME";
    $ev = new Event($name);
    $this->assertFalse($ev->isResultRequested());
    $ev->setResultRequested();
    $this->assertTrue($ev->isResultRequested());

  }

  public function testGetName() {
    $name = "TESTNAME";
    $ev = new Event($name);
    $this->assertEquals($name, $ev->getName());
  } 
  
  public function testSetName() {
    $newname = "NEWNAME";
    $name = "TESTNAME";
    $ev = new Event($name);
    $this->assertEquals($name, $ev->getName());
    $ev->setName($newname);
    $this->assertEquals($newname, $ev->getName());
  } 

  /**
   * @expectedException \InvalidArgumentException
   */
  public function testInvalidSetName() {
    $ev = new Event("NAME");
    $ev->setName(["hallo"]);
  }

  public function testGetBody() {
    $body = ["key" => "IAMTHEBODY"];
    $ev = new Event("NAME", $body, "");
    $this->assertEquals($body, $ev->getBody());
  }
}
