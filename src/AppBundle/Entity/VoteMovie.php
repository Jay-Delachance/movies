<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert; // nous donne accès à l'annotation Assert avec laquelle on teste si quelque chose est vrai
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Vote
 *
 * @HasLifecycleCallBacks()
 * @ORM\Table(name="voteMovie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoteMovieRepository")
 */
class VoteMovie
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
     * @var int
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Note minimale {{ limit }}",
     *      maxMessage = "Note maximale {{ limit }}"
     * )
     *
     * @ORM\Column(name="vote", type="integer")
     */
    private $vote;

    /**
     * @var Movie
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="votesMovie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="votesMovie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateVoted", type="datetime")
     */
    private $dateVoted;



    public function __construct()
    {

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

    /**
     * Set dateVoted
     *
     * @param \DateTime $dateVoted
     *
     * @return VoteMovie
     */
    public function setDateVoted($dateVoted)
    {
        $this->dateVoted = $dateVoted;

        return $this;
    }

    /**
     * Get dateVoted
     *
     * @return \DateTime
     */
    public function getDateVoted()
    {
        return $this->dateVoted;
    }



    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * @param Movie $movie
     */
    public function setMovie($movie)
    {
        $this->movie = $movie;
    }

    /**
     * @return int
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * @param int $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }


}
