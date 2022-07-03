<?php

namespace PremiumKickBot;

use Doctrine\ORM\{EntityManager, Exception\ORMException, OptimisticLockException};
use PremiumKickBot\DB\{Chat, User};


class Database
{

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(private EntityManager $entityManager)
    {
    }

    /**
     * @param int $id
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getUser(int $id): User
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (empty($user)) {
            $user = new User($id);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $user;
    }

    /**
     * @param int $id
     * @return Chat
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getChat(int $id): Chat
    {
        $chat = $this->entityManager->getRepository(Chat::class)->find($id);
        if (empty($chat)) {
            $chat = new Chat($id);
            $this->entityManager->persist($chat);
            $this->entityManager->flush();
        }
        return $chat;
    }

    public function saveEntity(object $entity): bool
    {
        try {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (OptimisticLockException|ORMException) {
            return false;
        }
        return true;
    }

    public function deleteEntity(object $entity): bool
    {
        try {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (OptimisticLockException|ORMException) {
            return false;
        }
        return true;
    }

    /**
     * @return int
     */
    public function countUsers(): int
    {
        return $this->entityManager->getRepository(User::class)->count([]);
    }

    /**
     * @return int
     */
    public function countChats(): int
    {
        return $this->entityManager->getRepository(Chat::class)->count([]);
    }

}