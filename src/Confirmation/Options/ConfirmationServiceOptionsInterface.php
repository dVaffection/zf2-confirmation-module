<?php

namespace Confirmation\Options;

interface ConfirmationServiceOptionsInterface
{

    /**
     * set confirmation entity class name
     *
     * @param string $classname
     * @return ModuleOptions
     */
    public function setConfirmationEntityClass($classname);

    /**
     * get confirmation entity class name
     *
     * @return string
     */
    public function getConfirmationEntityClass();

    public function getExpirationPeriod();

    public function setExpirationPeriod($expirationPeriod);
}
