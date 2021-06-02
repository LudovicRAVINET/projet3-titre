<?php

namespace App\Form;

use App\Entity\Wedding;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class WeddingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('eventDescription', TextareaType::class, [
                'label' => "Description de l'évènement : ",
            ])
            ->add('eventPicture', FileType::class, [
                'label' => 'Photo',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/bmp',
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Merci de téléverser une photo valide.',
                    ])
                ],
            ])
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
