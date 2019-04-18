<?php

namespace ReclamationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponsereclamation
 *
 * @ORM\Table(name="reponsereclamation")
 * @ORM\Entity
 */
class Reponsereclamation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=200, nullable=false)
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="string", length=200, nullable=false)
     */
    private $reponse;

    /**
     * @var string
     *
     * @ORM\Column(name="dateReclamation", type="string", length=255, nullable=true)
     */
    private $datereponse;


    /**
     * @var \ReclamationBundle\Entity\Reclamation
     *
     * @ORM\ManyToOne(targetEntity="Reclamation")
     * @ORM\JoinColumn(name="idreclamation", referencedColumnName="id")
     */
    private $idreclamation;

    /**
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     *   @ORM\JoinColumn(name="idadmin", referencedColumnName="id")
     * })
     */
    private $idadmin;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * @param string $sujet
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    }
    /**
     * @return string
     */
    public function getdatereponse()
    {
        return $this->datereponse;
    }

    /**
     * @param string $datereponse
     */
    public function setdatereponse($datereponse)
    {
        $this->datereponse = $datereponse;
    }


    /**
     * @return string
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * @param string $reponse
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;
    }

    /**
     * @return \ReclamationBundle\Entity\Reclamation
     */
    public function getIdreclamation()
    {
        return $this->idreclamation;
    }

    /**
     * @param \ReclamationBundle\Entity\Reclamation $idreclamation
     */
    public function setIdreclamation($idreclamation)
    {
        $this->idreclamation = $idreclamation;
    }

    /**
     */
    public function getIdadmin()
    {
        return $this->idadmin;
    }

    /**
     * @param \UserBundle\Entity\User $idadmin
     */
    public function setIdadmin($idadmin)
    {
        $this->idadmin = $idadmin;
    }


}

