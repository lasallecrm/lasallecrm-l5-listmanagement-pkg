<?php

namespace Lasallecrm\Listmanagement;

// this file created in order to try nitpick-ci.com.

// https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

use FooInterface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class AdamWathan extends Bar implements FooInterface 
{
    public function sampleFunction($a, $b = null) {
        if ($a === $b) { 
            bar();
        } 
        elseif ($a > $b) { 
            $foo->bar($arg1);
        } else { 
            BazClass::bar($arg2, $arg3);
        } 
    }

    final public static function bar() {
        // method body
    }
}


