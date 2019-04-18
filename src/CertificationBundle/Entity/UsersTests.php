<?php

namespace CertificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsersTests
 *
 * @ORM\Table(name="users_tests")
 * @ORM\Entity(repositoryClass="CertificationBundle\Repository\UsersTestsRepository")
 */
class UsersTests
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
     * @ORM\ManyToOne(targetEntity="Test")
     * @ORM\JoinColumn(name="id_test", referencedColumnName="id_test")
     */
    private $test_id;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user_id;
    /**
     * @ORM\Column(name="status", type="string", length=100, nullable=true)
     */
    private $status;
    /**
     * @ORM\Column(name="nbr_essai", type="integer", nullable=true)
     */
    private $nbr_essai;
    /**
     * @ORM\Column(name="submition", type="array" , nullable=true)
     */
    private $submition;
    /**
     * @ORM\Column(name="score", type="integer")
     */
    private $score;
    /**
     * @ORM\Column(name="correction", type="array")
     */
    private $correction;

    /**
     * @return array
     */
    public function getCorrection()
    {
        return $this->correction;
    }

    /**
     * @param array $correction
     */
    public function setCorrection($correction)
    {
        $this->correction = $correction;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }


    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $test_id
     */

    public function setTestId($test_id)
    {
        $this->test_id = $test_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $nbr_essai
     */
    public function setNbrEssai($nbr_essai)
    {
        $this->nbr_essai = $nbr_essai;
    }

    /**
     * @return array
     */
    public function getSubmition() :array
    {
        return $this->submition;
    }

    /**
     * @param mixed $submition
     */
    public function setSubmition($submition)
    {
        $this->submition = $submition;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getnbr_essai()
    {
        return $this->nbr_essai;
    }

    /**
     * @return Test
     */
    public function gettest_id()
    {
        return $this->test_id;
    }

    /**
     * @return mixed
     */
    public function getuser_id()
    {
        return $this->user_id;
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
    public function __construct()
    {
        $this->submition = array();
    }
}

