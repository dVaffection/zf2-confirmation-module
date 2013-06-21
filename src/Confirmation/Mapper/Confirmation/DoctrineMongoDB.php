<?php

namespace Confirmation\Mapper\Confirmation;

use Confirmation\Mapper;
use Confirmation\Entity;
use Confirmation\Options\ModuleOptions;
use Doctrine\ODM\MongoDB\DocumentManager;

class DoctrineMongoDB implements Mapper\Confirmation\ConfirmationInterface
{

    /**
     * @param DocumentManager
     */
    protected $dm;

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function __construct(DocumentManager $dm, ModuleOptions $options)
    {
        $this->setDocumentManager($dm);
        $this->options = $options;
    }

    public function create($controller, $action, array $params = array())
    {
        $dm    = $this->getDocumentManager();
        $class = $this->options->getConfirmationEntityClass();

        $document = new $class;
        if (!$document instanceof Entity\ConfirmationInterface) {
            throw new \RuntimeException('confirmation_entity_class must implement Confirmation\Entity\ConfirmationInterface');
        }

        $document->setController($controller);
        $document->setAction($action);
        $document->setParams($params);
        $document->setCreatedAt(new \DateTime);

        $dm->persist($document);
        $dm->flush();

        return $document->getId();
    }

    public function find($id)
    {
        $dm    = $this->getDocumentManager();
        $class = $this->options->getConfirmationEntityClass();

        return $dm->getRepository($class)->find($id);
    }

    public function delete($id)
    {
        $dm    = $this->getDocumentManager();
        $class = $this->options->getConfirmationEntityClass();

        return $dm->getRepository($class)->createQueryBuilder($class)
                ->remove()
                ->field('id')->equals($id)
                ->getQuery()
                ->execute()
        ;
    }

    public function cleanExpired()
    {
        $expiredAt = (new \DateTime())->sub(new \DateInterval($this->options->getExpirationPeriod()));

        $dm    = $this->getDocumentManager();
        $class = $this->options->getConfirmationEntityClass();

        return $dm->getRepository($class)->createQueryBuilder($class)
                ->remove()
                ->field('createdAt')->lt($expiredAt)
                ->getQuery()
                ->execute()
        ;
    }

    public function getDocumentManager()
    {
        return $this->dm;
    }

    public function setDocumentManager(DocumentManager $dm)
    {
        $this->dm = $dm;
        return $this;
    }

}