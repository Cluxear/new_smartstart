<?php

namespace CertificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Test
 *
 * @ORM\Table(name="test")
 * @ORM\Entity(repositoryClass="CertificationBundle\Repository\TestRepository")
 */
class Test
{ /**
     * @var int
     *
     * @ORM\Column(name="id_test", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_test;

    /**
     * @var string
     * @ORM\Column(name="titre_test", type="string", length=100)
     */
    private $titre_test;
    /**
     * @var int
     * @ORM\Column(name="passing_score", type="integer")
     */
    private $passing_score;
/**
* @ORM\Column(name="questions", type="array",nullable=true)
*/
    private $questions;

    /**
     * @ORM\Column(name="image", type="string")
     */
    private $image;

    /**
     * @ORM\Column(name="level", type="string")
     */
    private $level;
    /**
     * @ORM\Column(name="summary", type="string")
     */
    private $summary;

    /**
     * @ORM\Column(name="success", type="integer")
     */
    private $success;

    /**
     * @ORM\Column(name="failure", type="integer")
     */
    private $failure;
    /**
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost;
    /**
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @param int $id_test
     */
    public function setIdTest(int $id_test)
    {
        $this->id_test = $id_test;
    }

    /**
     * @param int $passing_score
     */
    public function setPassingScore(int $passing_score)
    {
        $this->passing_score = $passing_score;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    /**
     * @return mixed
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param mixed $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return mixed
     */
    public function getFailure()
    {
        return $this->failure;
    }

    /**
     * @param mixed $failure
     */
    public function setFailure($failure)
    {
        $this->failure = $failure;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }


    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }
    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */


    public function getquestions()
    {
        return $this->questions;
    }

    public function __construct() {
        $questions = array();
    }


    public function gettitre_test()
    {
        return $this->titre_test;
    }
    public function getTitreTest()
    {
        return $this->titre_test;
    }
    /**
    /**

    // ...


    /**
     * @param mixed $titre_test
     */
    public function setTitreTest($titre_test)
    {
        $this->titre_test = $titre_test;
    }

    public function getPassingScore()
    {
        return $this->passing_score;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getid_test()
    {
        return $this->id_test;
    }
}

