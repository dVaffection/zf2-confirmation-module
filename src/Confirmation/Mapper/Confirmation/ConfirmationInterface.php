<?php

namespace Confirmation\Mapper\Confirmation;

interface ConfirmationInterface
{

    /**
     * Create confirmation. Return hash string (ID)
     *
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return string
     */
    public function create($controller, $action, array $params = array());

    /**
     * Find confirmation by hash string (ID)
     * 
     * @param string $id
     * @return \Confirmation\ConfirmationInterface|null
     */
    public function find($id);

    /**
     * Remove confirmation by hash string (ID). 
     *
     * @param string $id
     * @return boolean
     */
    public function delete($id);

    /**
     * Remove expired confirmations
     *
     */
    public function cleanExpired();
}