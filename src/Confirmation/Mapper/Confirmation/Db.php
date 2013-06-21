<?php

namespace Confirmation\Mapper\Confirmation;

use Confirmation\Mapper\Confirmation\ConfirmationInterface as ConfirmationMapperInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Confirmation\Options\ModuleOptions;

class Db extends AbstractTableGateway implements ConfirmationMapperInterface
{

    /**
     * @var string
     */
    protected $table = 'confirmations';

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function __construct(Adapter $adapter, ModuleOptions $options)
    {
        $this->adapter = $adapter;
        $this->options = $options;

        $this->resultSetPrototype = new ResultSet();
        $entityClass              = $options->getConfirmationEntityClass();
        $this->resultSetPrototype->setArrayObjectPrototype(new $entityClass);

        $this->initialize();
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
        $id = $this->_generateId();

        $this->insert(array(
            'id'         => $id,
            'controller' => (string)$controller,
            'action'     => (string)$action,
            'params'     => serialize($params),
            'created_at' => date('Y-m-d H:i:s'),
        ));

        return $id;
    }

    protected function _generateId()
    {
        $attempt = 0;

        while (true) {
            if ($attempt >= 5) {
                throw new \RuntimeException('Was not able to generate id for 5 attempts');
            }

            $id  = md5(uniqid(rand()) . getmypid());
            $sql = sprintf('SELECT COUNT(*) AS cnt FROM %s WHERE id = :id',
                           $this->table);
            $rs  = $this->adapter->query($sql, array(':id' => $id));

            if ($rs->current()->cnt) {
                $attempt++;
            } else {
                return $id;
            }
        }
    }

    /**
     * Find confirmation by hash string (ID)
     *
     * @param string $id
     * @return \Confirmation\ConfirmationInterface|null
     */
    public function find($id)
    {
        return $this->select(array('id' => $id))->current();
    }

    /**
     * Remove confirmation by hash string (ID).
     *
     * @param string $id
     * @return boolean
     */
    public function delete($id)
    {
        return (bool)parent::delete(array('id' => $id));
    }

    public function cleanExpired()
    {
        $expiredAt = (new \DateTime())->sub(new \DateInterval($this->options->getExpirationPeriod()));

        parent::delete(array('created_at < ?' => $expiredAt->format('Y-m-d H:i:s')));
    }

}