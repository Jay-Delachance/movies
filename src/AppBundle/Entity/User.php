<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

// nous donne accès à l'annotation Assert avec laquelle on teste si quelque chose est vrai

/**
 * User
 *
 *
 * @UniqueEntity("username", message="Ce pseudo n'est pas dispo !")
 * @UniqueEntity("email", message="Cet email est déjà inscrit ici !")
 * @HasLifecycleCallBacks()
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var watchlistItem
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WatchlistItem", mappedBy="user")
     */

    private $watchlistItem;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity= "AppBundle\Entity\VoteMovie", mappedBy="user")
     */
    private $votesMovie;


    /**
     * @var string
     *
     * @Assert\Length(
     *     min=4,
     *     max=30
     * )
     * @Assert\NotBlank(message="Veuillez choisir un pseudo !")
     * @Assert\Regex("/^[A-Za-z0-9_-]+$/", message="Votre pseudo doit matcher avec notre regex !")
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="comments")
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=8,
     *     max=4000
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="roles", type="string", length=30)
     */
    private $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRegistered", type="datetime")
     */
    private $dateRegistered;


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
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roles
     *
     * @param string $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return string
     */
    public function getRoles()
    {
        // on retourne un tableau qui contient un rôle donc entre crochets
        return [$this->roles];
    }

    /**
     * Set dateRegistered
     *
     * @param \DateTime $dateRegistered
     *
     * @return User
     */
    public function setDateRegistered($dateRegistered)
    {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    /**
     * Get dateRegistered
     *
     * @return \DateTime
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }

    // méthodes getSalt et eraseCredentials inutiles mais obligatoires du UserInterface
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return ArrayCollection
     */
    public function getVotesMovie()
    {
        return $this->votesMovie;
    }

    /**
     * @param ArrayCollection $votesMovie
     */
    public function setVotesMovie($votesMovie)
    {
        $this->votesMovie = $votesMovie;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votesMovie = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add votesMovie
     *
     * @param \AppBundle\Entity\VoteMovie $votesMovie
     *
     * @return User
     */
    public function addVotesMovie(\AppBundle\Entity\VoteMovie $votesMovie)
    {
        $this->votesMovie[] = $votesMovie;

        return $this;
    }

    /**
     * Remove votesMovie
     *
     * @param \AppBundle\Entity\VoteMovie $votesMovie
     */
    public function removeVotesMovie(\AppBundle\Entity\VoteMovie $votesMovie)
    {
        $this->votesMovie->removeElement($votesMovie);
    }

    /**
     * @return watchlistItem
     */
    public function getWatchlistItem()
    {
        return $this->watchlistItem;
    }

    /**
     * @param watchlistItem $watchlistItem
     */
    public function setWatchlistItem($watchlistItem)
    {
        $this->watchlistItem = $watchlistItem;
    }


    /**
     * Add watchlistItem
     *
     * @param \AppBundle\Entity\WatchlistItem $watchlistItem
     *
     * @return User
     */
    public function addWatchlistItem(\AppBundle\Entity\WatchlistItem $watchlistItem)
    {
        $this->watchlistItem[] = $watchlistItem;

        return $this;
    }

    /**
     * Remove watchlistItem
     *
     * @param \AppBundle\Entity\WatchlistItem $watchlistItem
     */
    public function removeWatchlistItem(\AppBundle\Entity\WatchlistItem $watchlistItem)
    {
        $this->watchlistItem->removeElement($watchlistItem);
    }
}
