<?php

namespace ConfirmationTest\Entity;

use Confirmation\Entity;

class ConfirmationTest extends \PHPUnit_Framework_TestCase
{

    public function testExchangeArray()
    {
        $data = [
            'id'         => new \MongoId,
            'controller' => time(),
            'action'     => time(),
            'params'     => serialize([
                time(),
                time(),
            ]),
            'created_at' => date('c'),
        ];

        $doc = new Entity\Confirmation();
        $doc->exchangeArray($data);

        $this->assertEquals($data['id'], $doc->getId());
        $this->assertEquals($data['controller'], $doc->getController());
        $this->assertEquals($data['action'], $doc->getAction());
        $this->assertEquals(unserialize($data['params']), $doc->getParams());
        $this->assertEquals($data['created_at'],
                            $doc->getCreatedAt()->format('c'));
    }

}