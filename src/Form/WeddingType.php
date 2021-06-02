<?php

namespace App\Form;

use App\Entity\Wedding;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeddingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('eventDescription', TextareaType::class, [
                'label' => "Description de l'évènement : ",
            ])
            ->add('eventPicture')
            ->add('husbandFirstname', TextType::class, [
                'label' => 'Nom du mari : ',
            ])
            ->add('husbandLastname', TextType::class, [
                'label' => 'Prénom du mari : ',
            ])
            ->add('wifeFirstname', TextType::class, [
                'label' => 'Nom de la femme : ',
            ])
            ->add('wifeLastname', TextType::class, [
                'label' => 'Prénom de la femme : ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wedding::class,
        ]);
    }
}
