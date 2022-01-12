<?php


namespace App\Controller\Admin;


use App\Entity\Contact;

class ContactController extends Crud
{

    public function getEntity(): string
    {
        return Contact::class;
    }

    public function isCreatable(): bool
    {
        return false;
    }

    public function isEditable($entity): bool
    {
        return false;
    }

    public function isViewable($entity): bool
    {
        return true;
    }

}