<?php
namespace Gepard;

class EventFactoryTest extends \PHPUnit_Framework_TestCase {
  public function testEventFromJSON() {
    $ev = new Event("TESTEVENT");
    $factory = new EventFactory();
    $newevent = $factory->eventFromJSON($ev->toJSON());
    $this->assertEquals($ev->toJSON(), $newevent->toJSON());
  }
}
