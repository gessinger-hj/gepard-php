<?php
namespace Gepard;

class EventFactory {
  public function eventFromJSON($json) {
    return Event::fromJSON($json);
  }
}
