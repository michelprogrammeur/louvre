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
        return $this->getEntityManager()->createQueryBuilder()
            ->select("sum(c.quantity)")
            ->from("AppBundle:Command", "c")
            ->where("c.visitDate = :visit_date")
            ->setParameter('visit_date', $visit_date)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
