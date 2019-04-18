<?php

/**
 * Created by PhpStorm.
 * User: émine
 * Date: 08/04/2017
 * Time: 18:16
 */

use Doctrine\ORM\EntityRepository;

class ReclamationRechercheRepository extends EntityRepository
{
    function GetAdmins()
    {
        $query=$this->getEntityManager()->createQuery("SELECT a FROM UserBundle:User a WHERE a.roles LIKE '%ADMIN%'");
        return $query->getResult();
    }

}