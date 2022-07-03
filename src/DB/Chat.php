<?php

namespace PremiumKickBot\DB;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="chats")
 */
class Chat
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="integer")
     */
    private int $permissions = 0;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $inviteLink = null;

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
     * @return int
     */
    public function getPermissions(): int
    {
        return $this->permissions;
    }

    /**
     * @param int $permissions
     * @return Chat
     */
    public function setPermissions(int $permissions): Chat
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInviteLink(): ?string
    {
        return $this->inviteLink;
    }

    /**
     * @param string|null $inviteLink
     * @return Chat
     */
    public function setInviteLink(?string $inviteLink = null): Chat
    {
        $this->inviteLink = $inviteLink;
        return $this;
    }

    /**
     * @param int $permission
     * @return bool
     */
    public function checkPermission(int $permission): bool
    {
        return (bool)($this->permissions & $permission);
    }


}