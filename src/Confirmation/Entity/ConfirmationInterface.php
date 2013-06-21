<?php

namespace Confirmation\Entity;

interface ConfirmationInterface
{

    public function getId();

    public function setId($id);

    public function getController();

    public function setController($controller);

    public function getAction();

    public function setAction($action);

    public function getParams();

    public function setParams(array $params = array());

    public function getCreatedAt();

    public function setCreatedAt(\DateTime $createdAt);

    public function exchangeArray(array $data);

}