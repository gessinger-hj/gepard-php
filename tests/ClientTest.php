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
  protected function tearDown()  {
    test::clean(); // remove all registered test doubles
  }

  public function getClient() {
    $create = test::func("Gepard", "socket_create", "HANDLE");
    $connect = test::func("Gepard", "socket_connect", true);
    $cl = new Client();
    $create->verifyInvoked();
    $connect->verifyInvoked();
    

    return $cl;
  }

  public function testCreateSuccess() {
    $this->getClient();
  }

  /**
   * @expectedException \Exception
   */
  public function testCreateFailure() {
    $create = test::func("Gepard", "socket_create", "HANDLE");
    $connect = test::func("Gepard", "socket_connect", false);
    $cl = new Client();
    $create->verifyInvoked();
    $connect->verifyInvoked();
  }

  public function testEmit() {
    
    $cl = $this->getClient();

    $write = test::func("Gepard", "socket_write", "");

    $event = new Event("");
    $event_proxy = test::double($event, ["toJSON" => "JSONDUMMY"]);

    $cl->emit($event);
    
    $event_proxy->verifyInvoked("toJSON");
    $write->verifyInvoked(["HANDLE", "JSONDUMMY"]);

  }

  public function testRequest() {
    $cl = $this->getClient();

    $name = "EVENTNAME";
    $body = array("BODY" => "VALUE");

    $json = '{"className":"Event","name":"EVENTNAME","type":"","control":{"createdAt":"2016-09-10T18:37:59+0000","plang":"PHP","_isResultRequested":true},"body":{"BODY":"VALUE"}}';
 
    $write = test::func("Gepard", "socket_write", "");

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

    $event = test::double("Gepard\Event", ["fromJSON" => "FAKE_JSON"]);

    $res = $cl->request($name , $body); 
    $write->verifyInvoked();
    $event->verifyInvoked('fromJSON');
    $this->assertEquals($res, "FAKE_JSON");
     
  }
}
