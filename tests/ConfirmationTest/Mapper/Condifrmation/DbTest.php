<?php

namespace ConfirmationTest\Mapper\Confirmation;

use Zend\Db\ResultSet\ResultSet;

class DbTest extends \PHPUnit_Framework_TestCase
{

    public function testCanCreateAnEntityByItsId()
    {
        $controller = 'testController';
        $action     = 'testAction';
        $params     = ['key' => 'value'];

        $insertData = [
            'controller' => $controller,
            'action'     => $action,
            'params'     => serialize($params),
        ];

        $mockMapper = $this->getMock('Confirmation\Mapper\Confirmation\Db',
                                     ['insert', '_generateId'], [], '', false);
        $mockMapper->expects($this->once())
            ->method('insert')
            ->withAnyParameters($insertData);


        $mockMapper->create($controller, $action, $params);
    }

    public function testCanFindAnEntityByItsId()
    {
        $options     = \Bootstrap::getServiceManager()->get('confirmation_module_options');
        $entityClass = $options->getConfirmationEntityClass();
        $expected    = new $entityClass;
        $id          = time();
        $expected->setId($id);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new $entityClass);
        $resultSet->initialize(array($expected));

        $mockMapper = $this->getMock('Confirmation\Mapper\Confirmation\Db',
                                     ['select'], [], '', false);
        $mockMapper->expects($this->once())
            ->method('select')
            ->with(['id' => $id])
            ->will($this->returnValue($resultSet));


        $actual = $mockMapper->find($id);
        $this->assertSame($expected, $actual);
    }

    public function testCanDeleteAnEntityByItsId()
    {
        $id         = time();
        $mockMapper = $this->getMock('Confirmation\Mapper\Confirmation\Db',
                                     ['delete'], [], '', false);
        $mockMapper->expects($this->once())
            ->method('delete')
            ->with($id);

        $mockMapper->delete($id);
    }

}