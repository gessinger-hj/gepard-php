<?php
namespace Gepard;
//use PHPUnit\Framework\TestCase;
use Gepard\Client;
use Gepard\Event;
use phpmock\phpunit\PHPMock;
use phpmock\MockBuilder;

class ClientTest extends \PHPUnit_Framework_TestCase {

  use PHPMock;

  public function getClient() {
    $socket_create = $this->getFunctionMock(__NAMESPACE__, "socket_create");
    $socket_create->expects($this->once())->willReturn("HANDLE");
    $socket_connect = $this->getFunctionMock(__NAMESPACE__, "socket_connect");
    $socket_connect->expects($this->once())->willReturn(true);
    $cl = new Client();
    return $cl;
  }

  public function testCreateSuccess() {
    $this->getClient();
  }

  /**
   * @expectedException \Exception
   */
  public function testCreateFailure() {
    $socket_create = $this->getFunctionMock(__NAMESPACE__, "socket_create");
    $socket_create->expects($this->once())->willReturn("HANDLE");
    $socket_connect = $this->getFunctionMock(__NAMESPACE__, "socket_connect");
    $socket_connect->expects($this->once())->willReturn(false);

    $cl = new Client();
  }

  public function testEmit() {
    
    $cl = $this->getClient();

    $socket_write = $this->getFunctionMock(__NAMESPACE__, "socket_write");
    $socket_write->expects($this->once())->with($this->equalTo("HANDLE"), $this->equalTo("JSONDUMMY"));

    $event = $this->getMockBuilder("Gepard\Event")
                  ->disableOriginalConstructor()
                  ->setMethods(array("toJSON", "__construct"))
                  ->getMock();

    $event->expects($this->once())
          ->method("toJSON")
          ->willReturn("JSONDUMMY");


    $cl->emit($event);
  
  }

  public function testRequest() {
    $cl = $this->getClient();

    $name = "EVENTNAME";
    $body = array("BODY" => "VALUE");

    $write = '{"className":"Event","name":"EVENTNAME","type":"","control":{"createdAt":"2016-09-10T18:37:59+0000","plang":"PHP","_isResultRequested":true},"body":{"BODY":"VALUE"}}';

    $socket_write = $this->getFunctionMock(__NAMESPACE__, "socket_write");
    $socket_write->expects($this->once());

    $json = $write;
    $i = 0;

    $builder = new MockBuilder();
    $builder->setNamespace(__NAMESPACE__)
            ->setName("socket_recv")
            ->setFunction(function($handle, &$buf, $flag) use (&$i, &$json) {
              $ret = $json[$i];
              $buf = $ret;
              $i++;
            });
    $env = $builder->build();
    $env->enable();

    $res = $cl->request($name , $body); 

    $this->assertEquals($res, $json);
     
  }
}
