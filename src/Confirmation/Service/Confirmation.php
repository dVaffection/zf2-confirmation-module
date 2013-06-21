<?php

namespace Confirmation\Service;

use Confirmation\Mapper\Confirmation\ConfirmationInterface as ConfirmationMapperInterface;

class Confirmation 
{

    protected $_mapper;

    public function __construct(ConfirmationMapperInterface $mapper)
    {
        $this->setMapper($mapper);
    }

    /**
     * Create confirmation. Return hash string (ID)
     *
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return string
     */
    public function create($controller, $action, array $params = array())
    {
        return $this->getMapper()->create($controller, $action, $params);
    }

    /**
     * Find confirmation by hash string (ID)
     *
     * @param string $id
     * @return \Confirmation\Entity\ConfirmationInterface|null
     */
    public function find($id)
    {
        return $this->getMapper()->find($id);
    }

    /**
     * Remove confirmation by hash string (ID).
     *
     * @param string $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($id);
    }

    public function cleanExpired()
    {
        return $this->getMapper()->cleanExpired();
    }

    /**
     * Get confirmation mapper
     * 
     * @return ConfirmationMapperInterface
     */
    public function getMapper()
    {
        return $this->_mapper;
    }

    public function setMapper(ConfirmationMapperInterface $mapper)
    {
        $this->_mapper = $mapper;
    }

}