<?php

namespace ConfirmationTest\Mapper\Confirmation;

use Confirmation\Mapper\Confirmation\DoctrineMongoDB;

class DoctrineMongoDBTest extends \PHPUnit_Framework_TestCase
{

    public function testCanCreateAnEntityByItsId()
    {
        $controller = 'testController';
        $action     = 'testAction';
        $params     = ['key' => 'value'];

        $options     = \Bootstrap::getServiceManager()->get('confirmation_module_options');
        $entityClass = $options->getConfirmationEntityClass();

        $document = new $entityClass;
        $document->setController($controller);
        $document->setAction($action);
        $document->setParams($params);

        $mockDm = $this->getMock('Doctrine\ODM\MongoDB\DocumentManager',
                                 ['persist', 'flush'], [], '', false);
        $mockDm->expects($this->once())
            ->method('persist')
            ->withAnyParameters($document);

        $mapper = new DoctrineMongoDB($mockDm, $options);
        $mapper->create($controller, $action, $params);
    }

    public function testCanFindAnEntityByItsId()
    {
        $id = time();

        $options     = \Bootstrap::getServiceManager()->get('confirmation_module_options');
        $entityClass = $options->getConfirmationEntityClass();
        $expected    = new $entityClass;


        $mockRepo = $this->getMock('Doctrine\ODM\MongoDB\DocumentRepository',
                                   ['find'], [], '', false);
        $mockRepo->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($expected));

        $mockDm = $this->getMock('Doctrine\ODM\MongoDB\DocumentManager',
                                 ['getRepository'], [], '', false);
        $mockDm->expects($this->once())
            ->method('getRepository')
            ->withAnyParameters($entityClass)
            ->will($this->returnValue($mockRepo));

        $mapper = new DoctrineMongoDB($mockDm, $options);
        $actual = $mapper->find($id);

        $this->assertSame($expected, $actual);
    }

    public function testCanDeleteAnEntityByItsId()
    {
        $id = time();

        $options     = \Bootstrap::getServiceManager()->get('confirmation_module_options');
        $entityClass = $options->getConfirmationEntityClass();

        // here comes a long chain of mocks...
        $mockQuery = $this->getMock('Doctrine\ODM\MongoDB\Query\Query',
                                    ['execute'], [], '', false);

        $mockQueryBuilder = $this->getMock('Doctrine\ODM\MongoDB\Query\Builder',
                                           ['remove', 'field', 'equals', 'getQuery'],
                                           [], '', false);
        $mockQueryBuilder->expects($this->once())
            ->method('remove')
            ->will($this->returnValue($mockQueryBuilder));
        $mockQueryBuilder->expects($this->once())
            ->method('field')
            ->will($this->returnValue($mockQueryBuilder));
        $mockQueryBuilder->expects($this->once())
            ->method('equals')
            ->with($id)
            ->will($this->returnValue($mockQueryBuilder));
        $mockQueryBuilder->expects($this->once())
            ->method('getQuery')
            ->will($this->returnValue($mockQuery));

        $mockRepo = $this->getMock('Doctrine\ODM\MongoDB\DocumentRepository',
                                   ['createQueryBuilder'], [], '', false);
        $mockRepo->expects($this->once())
            ->method('createQueryBuilder')
            ->with($entityClass)
            ->will($this->returnValue($mockQueryBuilder));

        $mockDm = $this->getMock('Doctrine\ODM\MongoDB\DocumentManager',
                                 ['getRepository'], [], '', false);
        $mockDm->expects($this->once())
            ->method('getRepository')
            ->withAnyParameters($entityClass)
            ->will($this->returnValue($mockRepo));

        $mapper = new DoctrineMongoDB($mockDm, $options);
        $mapper->delete($id);
    }

}