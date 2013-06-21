<?php

namespace ConfirmationTest\Service;

use ConfirmationTest\Mapper;
use Confirmation\Service;
use Confirmation\Options\ModuleOptions;

class ConfirmationTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $options = new ModuleOptions;
        $mapper  = new Mapper\Confirmation\Mock($options);
        $service = new Service\Confirmation($mapper);


        $expected = $service->create('testController', 'testAction', []);
        $entity   = $service->find($expected);

        $this->assertEquals($expected, $entity->getId());
    }

    public function testDelete()
    {
        $options = new ModuleOptions;
        $mapper  = new Mapper\Confirmation\Mock($options);
        $service = new Service\Confirmation($mapper);


        $id     = $service->create('testController', 'testAction', []);
        $service->delete($id);
        $actual = $service->find($id);

        $this->assertEmpty($actual);
    }

    public function testCleanExpired()
    {
        $options = new ModuleOptions([
            'expirationPeriod' => 'PT1S',
        ]);
        $mapper  = new Mapper\Confirmation\Mock($options);
        $service = new Service\Confirmation($mapper);


        $id     = $service->create('testController', 'testAction', []);
        sleep(2);
        $service->cleanExpired();
        $actual = $service->find($id);

        $this->assertEmpty($actual);
    }

}