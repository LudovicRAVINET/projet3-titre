<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;


class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('page', ChoiceType::class, [
            'choices'  => [
                'Mention légal' => 'mention legal',
                'Accueil' => 'accueil',
                'Mariage' => 'mariage',
                'Anniversaire' => 'anniversaire',
                'Deuil' => 'deuil',
                'Naissance' => 'naissance',

            ],
            
        ])
            ->add('content', CKEditorType::class);
            //Page a modifier...
    }
}
