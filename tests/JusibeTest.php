<?php

/*
 * This file is part of the Jusibe PHP library.
 *
 * (c) Prosper Otemuyiwa <prosperotemuyiwa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unicodeveloper\Jusibe\Test;

use ReflectionClass;
use PHPUnit_Framework_TestCase;
use Unicodeveloper\Jusibe\Jusibe;

class JusibeTest extends PHPUnit_Framework_TestCase
{
    protected $jusibe;

    public function setUp()
    {
        $this->jusibe = new Jusibe();
    }

    public function testSendSMS()
    {
        $this->assertTrue(true);
    }
}