<?php

/*
 * This file is part of the shawsola/easyredis.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Easy\Exception;

use Exception;

class ResponseException extends Exception
{
    public function __toString()
    {
        return $this->message;
    }
}
