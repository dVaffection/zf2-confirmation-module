<?php

namespace Confirmation\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
    implements ConfirmationServiceOptionsInterface
{

    protected $confirmationEntityClass;
    protected $expirationPeriod;

    /**
     * set confirmation entity class name
     *
     * @param string $classname
     * @return ModuleOptions
     */
    public function setConfirmationEntityClass($classname)
    {
        $this->confirmationEntityClass = $classname;
        return $this;
    }

    /**
     * get confirmation entity class name
     *
     * @return string
     */
    public function getConfirmationEntityClass()
    {
        return $this->confirmationEntityClass;
    }

    public function getExpirationPeriod()
    {
        return $this->expirationPeriod;
    }

    public function setExpirationPeriod($expirationPeriod)
    {
        $this->expirationPeriod = $expirationPeriod;
    }

}
