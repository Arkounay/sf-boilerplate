<?php


namespace App\Controller\Admin;

use App\Entity\News;

class NewsController extends Crud
{

    public function getEntity(): string
    {
        return News::class;
    }

    public function getPluralName(): string
    {
        return 'Actualités';
    }

}