<?php

namespace Easy\Exception;

class ResponseException extends \Exception
{
  public function __toString()
  {
      return $this->message;
  }
}