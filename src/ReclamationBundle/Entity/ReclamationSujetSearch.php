<?php
/**
 * Created by PhpStorm.
 * User: émine
 * Date: 09/04/2017
 * Time: 18:33
 */

namespace ReclamationBundle\Entity;


class ReclamationSujetSearch
{

    private $sujet;

    /**
     * @return mixed
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * @param mixed $sujet
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    }
}