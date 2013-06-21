<?php

namespace ConfirmationTest\Options;

use Confirmation\Options\ModuleOptions;

class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{

    public function testSetConfirmationEntityClass()
    {
        $expected = time();

        $options = new ModuleOptions([
            'confirmationEntityClass' => $expected,
        ]);
        $actual  = $options->getConfirmationEntityClass();
        $this->assertEquals($expected, $actual);


        $options = new ModuleOptions;
        $options->setConfirmationEntityClass($expected);
        $actual  = $options->getConfirmationEntityClass();
        $this->assertEquals($expected, $actual);
    }

    public function testSetExpirationPeriod()
    {
        $expected = time();

        $options = new ModuleOptions([
            'expirationPeriod' => $expected,
        ]);
        $actual  = $options->getExpirationPeriod();
        $this->assertEquals($expected, $actual);

        $options = new ModuleOptions;
        $options->setExpirationPeriod($expected);
        $actual  = $options->getExpirationPeriod();
        $this->assertEquals($expected, $actual);
    }

}