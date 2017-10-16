<?php
namespace Safaricom\Mpesa\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Mpesa
 * @package Safaricom\Mpesa\Facade
 */
class Mpesa extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Mpesa';
    }


}