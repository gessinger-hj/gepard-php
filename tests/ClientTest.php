<?php
namespace Gepard;
//use PHPUnit\Framework\TestCase;
use Gepard\Client;
use Gepard\Event;
use phpmock\phpunit\PHPMock;
use phpmock\MockBuilder;
use AspectMock\Test as test;

class ClientTest extends \PHPUnit_Framework_TestCase {

  use PHPMock;

  public function getClient($data = "", $event_factory = null) {
    $socket = new MockSocket($data); 
    $factory = $this->getMockBuilder("\Socket\Raw\Factory")
                    ->disableOriginalConstructor()
                    ->setMethods(["createClient"])
                    ->getMock();
    
    $factory->expects($this->once())
            ->method("createClient")
            ->willReturn($socket);
    
    $cl = new Client(4332, "localhost", $factory, $event_factory);
    return $cl;
  }

  public function testCreateSuccess() {
    $this->getClient();
  }

  /**
   * @expectedException \Socket\Raw\Exception
   */
  public function testCreateFailure() {
    $cl = new Client();
  }

  public function testEmit() {
    $socket = $this->getMockBuilder("\Socket\Raw\Socket")
                   ->disableOriginalConstructor()
                   ->setMethods(["write"])
                   ->getMock();

    $socket->expects($this->once())->method("write")->with("JSONDUMMY");

  //   $factory = $this->getMockBuilder("\Socket\Raw\Factory")
  //                   ->disableOriginalConstructor()
  //                   ->setMethods(["createClient"])
  //                   ->getMock();
    
  //   $factory->expects($this->once())
  //           ->method("createClient")
  //           ->willReturn($socket);

  //   $event = $this->getMockBuilder("Gepard\Event")
  //                 ->disableOriginalConstructor()
  //                 ->setMethods(array("toJSON", "__construct"))
  //                 ->getMock();

  //   $event->expects($this->once())
  //         ->method("toJSON")
  //         ->willReturn("JSONDUMMY");

  //   $cl = new Client(4332, "localhost", $factory);
  //   $cl->emit($event);
  
  }
  
  // public function testSetEventFactory() {
  //   $cl = $this->getClient();
  //   $cl->setEventFactory(new EventFactory());
  // }

  // public function testReadJSONBlock() {
  //   $json = json_encode(["hallo" => "welt", "list" => ["one", "two", "three"]]);
  //   $cl = $this->getClient($json);
  //   $block = $cl->readJSONBlock();
  //   $this->assertEquals($json, $block);
  // }

  // public function testListen() {
  //   $factory = $this->getMockBuilder("Gepard\EventFactory")
  //                   ->setMethods(["eventFromJSON"])
  //                   ->getMock();

  //   $evmock = $this->getMockBuilder("Gepard\Event")
  //                  ->disableOriginalConstructor()
  //                  ->setMethods(["getName"])
  //                  ->getMock();

  //   $json1 = '{"name": "event1"}';
  //   $json2 = '{"name": "event2"}';
  //   $json3 = '{"name": "event3"}';

  //   $evmock->expects($this->at(0))->method("getName")->willReturn("event1");
  //   $evmock->expects($this->at(1))->method("getName")->willReturn("event2");
  //   //$evmock->expects($this->at(2))->method("getName")->willReturn("event3");

  //   $factory->expects($this->at(0))->method("eventFromJSON")->with($json1)->willReturn($evmock);
  //   $factory->expects($this->at(1))->method("eventFromJSON")->with($json2)->willReturn($evmock);

  //   $json = $json1.$json2.$json3;

  //   $cl = $this->getClient($json, $factory);

  //   $cl->listen(["event2"]);
  // } 

  // public function testRequest() {

  //   $name = "EVENTNAME";
  //   $body = array("BODY" => "VALUE");

  //   $json = '{"className":"Event","name":"EVENTNAME","type":"","control":{"createdAt":"2016-09-10T18:37:59+0000","plang":"PHP","_isResultRequested":true},"body":{"BODY":"VALUE"}}';

  //   $ev = Event::fromJSON($json); 

  //   $factory = $this->getMockBuilder("Gepard\EventFactory")
  //                   ->setMethods(["eventFromJSON"])
  //                   ->getMock();
    
  //   $factory->expects($this->once())->method("eventFromJSON")->with($json)->willReturn($ev);
    
  //   $cl = $this->getClient($json, $factory);



  //   $res = $cl->request($name , $body);
  //   $this->assertEquals($ev, $res); 
  // }
}
