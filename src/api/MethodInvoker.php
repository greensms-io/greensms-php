<?php

namespace GreenSms\Api;

use \stdClass;

class MethodInvoker extends stdClass {
  public function __call($method, $args)
    {

        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }
}
