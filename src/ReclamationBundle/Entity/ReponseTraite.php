<?php

namespace ReclamationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReponseTraite
 *
 * @ORM\Table(name="reponsetraite", indexes={@ORM\Index(name="idtraite", columns={"id"})})
 * @ORM\Entity
 */
class ReponseTraite
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @var \ReclamationBundle\Entity\traitereclamation
     *
     * @ORM\ManyToOne(targetEntity="traitereclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtraite", referencedColumnName="id")
     * })
     */
    private $idtraite;



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
     * @return \ReclamationBundle\Entity\traitereclamation
     */
    public function getIdtraite()
    {
        return $this->idtraite;
    }

    /**
     * @param \ReclamationBundle\Entity\traitereclamation $idtraite
     */
    public function setIdtraite($idtraite)
    {
        $this->idtraite = $idtraite
        ;
    }



}

