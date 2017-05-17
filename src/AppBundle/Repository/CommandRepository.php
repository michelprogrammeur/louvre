<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query;

/**
 * CommandRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandRepository extends \Doctrine\ORM\EntityRepository
{
    public function countAllTicketsByDay($visit_date)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c FROM AppBundle:Command c WHERE c.visitDate = :visit_date
            ')
            ->setParameter('visit_date', $visit_date)
            ->getResult(Query::HYDRATE_ARRAY);
    }
}
