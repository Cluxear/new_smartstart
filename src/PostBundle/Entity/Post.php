<?php

namespace PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="PostBundle\Repository\PostRepository")
 */
class Post
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
     * @var string
     *
     * @ORM\Column(name="titre_post", type="string", length=255)
     */
    private $titre_post;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePost", type="date")
     */
    private $datePost;

    /**
     * @var float
     *
     * @ORM\Column(name="cout", type="float")
     */
    private $cout;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float")
     */
    private $rating;

    /**
     * @var integer
     *
     * @ORM\Column(name="deadline", type="integer")
     */
    private $deadline;

    /**
     * @var string
     *
     * @ORM\Column(name="type_payemet", type="string", length=255)
     */
    private $type_payemet;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user_id;

    /**
     * @return string
     */
    public function getTitrePost(): string
    {
        return $this->titre_post;
    }

    /**
     * @param string $titre_post
     */
    public function setTitrePost(string $titre_post)
    {
        $this->titre_post = $titre_post;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getDatePost(): \DateTime
    {
        return $this->datePost;
    }

    /**
     * @param \DateTime $datePost
     */
    public function setDatePost(\DateTime $datePost)
    {
        $this->datePost = $datePost;
    }

    /**
     * @return float
     */
    public function getCout(): float
    {
        return $this->cout;
    }

    /**
     * @param float $cout
     */
    public function setCout(float $cout)
    {
        $this->cout = $cout;
    }

    /**
     * @return float
     */
    public function getRating(): float
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     */
    public function setRating(float $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getDeadline(): int
    {
        return $this->deadline;
    }

    /**
     * @param int $deadline
     */
    public function setDeadline(int $deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * @return string
     */
    public function getTypePayemet(): string
    {
        return $this->type_payemet;
    }

    /**
     * @param string $type_payemet
     */
    public function setTypePayemet(string $type_payemet)
    {
        $this->type_payemet = $type_payemet;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

