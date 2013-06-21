<?php

namespace ConfirmationTest\Mapper\Confirmation;

use Confirmation\Mapper\Confirmation\ConfirmationInterface as ConfirmationMapperInterface;
use Confirmation\Entity;
use Confirmation\Options\ModuleOptions;

class Mock implements ConfirmationMapperInterface
{

    /**
     * Mock collection
     *
     * @var array
     */
    protected $collection = [];

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function __construct(ModuleOptions $options)
    {
        $this->options = $options;
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
        $id = new \MongoId();

        $doc = new Entity\Confirmation();
        $doc->setId($id);
        $doc->setController($controller);
        $doc->setAction($action);
        $doc->setParams($params);
        $doc->setCreatedAt(new \DateTime);

        $this->collection[(string) $id] = $doc;

        return $id;
    }

    /**
     * Find confirmation by hash string (ID)
     *
     * @param string $id
     * @return \Confirmation\ConfirmationInterface|null
     */
    public function find($id)
    {
        $id = (string) $id;

        return isset($this->collection[$id])
            ? $this->collection[$id]
            : false;
    }

    /**
     * Remove confirmation by hash string (ID).
     *
     * @param string $id
     * @return boolean
     */
    public function delete($id)
    {
        $id = (string) $id;

        unset($this->collection[$id]);
    }

    /**
     * Remove expired confirmations
     *
     */
    public function cleanExpired()
    {
        $expiredAt = (new \DateTime())->sub(new \DateInterval($this->options->getExpirationPeriod()));

        /* @var $doc Entity\Confirmation */
        foreach ($this->collection as $id => $doc) {
            if ($doc->getCreatedAt()->getTimestamp() < $expiredAt->getTimestamp()) {
                unset($this->collection[$id]);
            }
        }
    }

}