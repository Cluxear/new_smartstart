<?php
/**
 * Created by PhpStorm.
 * User: diabl
 * Date: 22/03/2019
 * Time: 13:58
 */

namespace ContractBundle\Repository;


use Doctrine\ORM\EntityRepository;


class ContractRepository extends EntityRepository
{
    public function getMyContracts($id){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM ContractBundle:contract c WHERE c.idApplication = :val'
            )
            ->setParameter('val', $id)
            ->getResult();

    }
    public function getMyLowContracts($id){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM AppBundle:contract c,AppBundle:application a,AppBundle:opportunity o WHERE ((c.idApplication = a.idApplication) AND (a.idOpportunity = o.idOpp) AND (o.idEntreprise = :val) AND (c.prio = 0))'
            )
            ->setParameter('val', $id)
            ->getResult();

    }
    public function freelancerContracts($id){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM ContractBundle:contract c WHERE c.idApplication = :val'
            )
            ->setParameter('val', $id)
            ->getResult();
    }

}