<?php

namespace Easy\Exception;

class ConnectException extends \Exception
{
  public function __toString()
  {
      return 'Connection Redis Error.';
  }
}
