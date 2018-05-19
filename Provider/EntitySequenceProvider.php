<?php

declare(strict_types=1);

/*
 * This file is part of the Indragunawan/sequence-bundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\SequenceBundle\Provider;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Indragunawan\SequenceBundle\Model\SequenceInterface;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class EntitySequenceProvider implements SequenceProviderInterface
{
    private $registry;
    private $entityClass;
    private $managerName;

    public function __construct(ManagerRegistry $registry, string $entityClass, ?string $managerName = null)
    {
        $this->registry = $registry;
        $this->entityClass = $entityClass;
        $this->managerName = $managerName;
    }

    public function getSequence(string $name, array $criteria = [], bool $lock = true): ?SequenceInterface
    {
        $repository = $this->getRepository();
        $qb = $repository->createQueryBuilder('s');
        if ($repository instanceof SequenceCriteriaInterface) {
            $qb->addCriteria($repository->getSequenceCriteria($name, $criteria));
        } else {
            $qb
                ->where($qb->expr()->eq('s.name', ':name'))
                ->setParameter('name', $name)
            ;

            foreach ($criteria as $key => $value) {
                $qb
                    ->andWhere($qb->expr()->eq('s.'.$key, ':'.$key))
                    ->setParameter(':'.$key, $value)
                ;
            }
        }

        $query = $qb->getQuery();
        if (true === $lock) {
            $query->setLockMode(LockMode::PESSIMISTIC_WRITE);
        }

        return $query
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    public function transactional(\Closure $func): string
    {
        return $this->getObjectManager()->transactional($func);
    }

    private function getObjectManager(): EntityManager
    {
        return $this->registry->getManager($this->managerName);
    }

    private function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->entityClass);
    }
}
