<?php


namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\Admin\UserType;

class UserController extends BaseCrud
{

    public function getEntity(): string
    {
        return User::class;
    }

    protected function createNew(): object
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        return $user;
    }

    protected function overrideFormType($entity, bool $creation): ?string
    {
        return UserType::class;
    }

    public function getName(): string
    {
        return 'Administrateur';
    }

    public function isSearchable(): bool
    {
        return false;
    }

    public function isDeletable($entity): bool
    {
        return $entity !== $this->getUser();
    }

}