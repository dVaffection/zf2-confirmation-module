<?php

namespace Confirmation\Entity;

use Confirmation\Entity\ConfirmationInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zend\Filter\Word\UnderscoreToCamelCase as UnderscoreToCamelCaseFilter;

/** @ODM\Document(collection="confirmations") */
class Confirmation implements ConfirmationInterface
{

    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\String
     */
    protected $controller;

    /**
     * @ODM\String
     */
    protected $action;

    /**
     * @ODM\Hash
     */
    protected $params = array();

    /**
     *
     * @ODM\Date
     */
    protected $createdAt;

    /**
     *
     * @var UnderscoreToCamelCaseFilter
     */
    protected $_wordFilter;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams(array $params = array())
    {
        $this->params = $params;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function exchangeArray(array $data)
    {
        foreach ($data as $key => $value) {

            $method = $this->_getMethodName($key);

            if ( ! method_exists($this, $method)) {
                continue;
            }

            switch ($key) {
                case 'params' :
                    if (is_string($value)) {
                        $value = unserialize($value);
                    }
                    break;

                case 'created_at' :
                    if ( ! $value instanceof \DateTime) {
                        $value = new \DateTime($value);
                    }
                    break;
            }

            call_user_func(array($this, $method), $value);
        }
    }

    protected function _getMethodName($key)
    {
        if (is_null($this->_wordFilter)) {
            $this->_wordFilter = new UnderscoreToCamelCaseFilter;
        }

        $formatted = $this->_wordFilter->filter($key);
        return 'set' . ucfirst($formatted);
    }

}