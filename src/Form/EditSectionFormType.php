<?php

namespace App\Form;

use App\Entity\Sections;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class EditSectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class)
            ->add('description',TextareaType::class)
            ->add('attachment',FileType::class,[
                'mapped' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '100M', 
                        'mimeTypes' => [
                            'video/mp4',
                            'video/mpeg',
                            'video/quicktime',
                            'video/x-ms-wmv',
                            'video/x-msvideo',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid video file (Max Size 100M)!',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sections::class,
        ]);
    }
}
