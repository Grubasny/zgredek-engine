<?php

namespace ZgredekEngine\Logger;

use DateTime;

function log($message, $context = []) 
{
    $backtrace = debug_backtrace();
    $caller = $backtrace[1];
    
    $class = $caller['class'] ?? null;
    $method = $caller['function'];
    
    print json_encode(
        [
            'class' => $class,
            'method' => $method,
            'message' => $message, 
            'date' => (new DateTime())->format('Y-m-d H:i:s.v'),
            'context' => $context
        ]
    ) . PHP_EOL;
}