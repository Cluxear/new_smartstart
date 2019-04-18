<?php

namespace ReclamationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Traitereclamation
 *
 * @ORM\Table(name="traitereclamation", indexes={@ORM\Index(name="idadmin", columns={"idadmin"}), @ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity
 */
class traitereclamation
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
     * @ORM\Column(name="traite", type="string", length=200, nullable=false)
     */
    private $traite;

    /**
     * @var string
     *
     * @ORM\Column(name="datetraite", type="string", length=255, nullable=true)
     */
    private $datetraite;

    /**
     * @var string
     *
     * @ORM\Column(name="statustraite", type="string", length=50, nullable=false)
     */
    private $statustraite;



    /**
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idagent", referencedColumnName="id")
     * })
     */
    private $idagent;

    /**
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idadmin", referencedColumnName="id")
     * })
     */
    private $idadmin;

    /**
     * @var \ReclamationBundle\Entity\Reclamation
     *
     * @ORM\ManyToOne(targetEntity="Reclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idreclamation", referencedColumnName="id")
     * })
     */
    private $idreclamation;



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
    public function getdatetraite()
    {
        return $this->datetraite;
    }

    /**
     * @param string $datetraite
     */
    public function setdatetraite($datetraite)
    {
        $this->datetraite = $datetraite;
    }


    /**
     * @return string
     */
    public function getTraite()
    {
        return $this->traite;
    }

    /**
     * @param string $reponse
     */
    public function setTraite($traite)
    {
        $this->traite = $traite;
    }

    /**
     */
    public function getIdagent()
    {
        return $this->idagent;
    }

    /**
     * @param \UserBundle\Entity\User $idagent
     */
    public function setIdagent($idagent)
    {
        $this->idagent = $idagent;
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

    /**
     * @return \ReclamationBundle\Entity\Reclamation
     */
    public function getIdreclamation()
    {
        return $this->idreclamation;
    }

    /**
     * @param \ReclamationBundle\Entity\Reclamation $id
     */
    public function setIdreclamation($id)
    {
        $this->idreclamation = $id;
    }

    /**
     * @return string
     */
    public function getStatustraite()
    {
        return $this->statustraite;
    }

    /**
     * @param string $statustraite
     */
    public function setStatustraite($statustraite)
    {
        $this->statustraite = $statustraite;
    }



}

