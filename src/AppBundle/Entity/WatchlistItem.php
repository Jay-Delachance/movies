<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WatchlistItem
 *
 * @ORM\Table(name="watchlist_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WatchlistItemRepository")
 */
class WatchlistItem
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
     * @var user
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="watchlistItem")
     */
    private $user;

    /**
     * @var movie
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="watchlistItem")
     */
    private $movie;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;


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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return WatchlistItem
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param user $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * @param movie $movie
     */
    public function setMovie($movie)
    {
        $this->movie = $movie;
    }


}
