<?php

namespace App\Controller\Admin;

use App\Entity\Notice;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NoticeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Notice::class;
    }
}
