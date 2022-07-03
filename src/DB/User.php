<?php

namespace PremiumKickBot\DB;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isAdmin = false;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isBlocked = false;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isWhitelisted = false;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     * @return User
     */
    public function setIsAdmin(bool $isAdmin): User
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }


    /**
     * @param bool $isBlocked
     * @return User
     */
    public function setIsBlocked(bool $isBlocked = false): User
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWhitelisted(): bool
    {
        return $this->isWhitelisted;
    }

    /**
     * @param bool $isWhitelisted
     * @return User
     */
    public function setIsWhitelisted(bool $isWhitelisted): User
    {
        $this->isWhitelisted = $isWhitelisted;
        return $this;
    }
}