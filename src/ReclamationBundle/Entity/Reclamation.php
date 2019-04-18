<?php

namespace ReclamationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclam")
 * @ORM\Entity(repositoryClass="ReclamationBundle\Repository\ReclamRepository")
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $id_client;
    /**
     * @var string
     *
     * @ORM\Column(name="sujetReclamation", type="string", length=200, nullable=false)
     */
    private $sujetreclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionReclamation", type="string", length=200, nullable=false)
     */
    private $descriptionreclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="dateReclamation", type="string", length=255, nullable=true)
     */
    private $datereclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="statusReclamation", type="string", length=50, nullable=false)
     */
    private $statusreclamation;

    /**
     * @return mixed
     */
    public function getIdClient()
    {
        return $this->id_client;
    }

    /**
     * @param mixed $id_client
     */
    public function setIdClient($id_client)
    {
        $this->id_client = $id_client;
    }
    public function getid_client()
    {
        return $this->id_client;
    }

    /**
     * @param mixed $id_client
     */
    public function setid_client($id_client)
    {
        $this->id_client = $id_client;
    }




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
    public function getSujetreclamation()
    {
        return $this->sujetreclamation;
    }

    /**
     * @param string $sujetreclamation
     */
    public function setSujetreclamation($sujetreclamation)
    {
        $this->sujetreclamation = $sujetreclamation;
    }

    /**
     * @return string
     */
    public function getDescriptionreclamation()
    {
        return $this->descriptionreclamation;
    }

    /**
     * @param string $descriptionreclamation
     */
    public function setDescriptionreclamation($descriptionreclamation)
    {
        $this->descriptionreclamation = $descriptionreclamation;
    }

    /**
     * @return string
     */
    public function getDatereclamation()
    {
        return $this->datereclamation;
    }

    /**
     * @param string $datereclamation
     */
    public function setDatereclamation($datereclamation)
    {
        $this->datereclamation = $datereclamation;
    }

    /**
     * @return string
     */
    public function getStatusreclamation()
    {
        return $this->statusreclamation;
    }

    /**
     * @param string $statusreclamation
     */
    public function setStatusreclamation($statusreclamation)
    {
        $this->statusreclamation = $statusreclamation;
    }

}

