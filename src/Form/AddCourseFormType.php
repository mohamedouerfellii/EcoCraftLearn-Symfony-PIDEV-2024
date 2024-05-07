<?php

namespace App\Form;

use App\Entity\Courses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AddCourseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image',FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please upload an image file !',
                    ]),
                    new Assert\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (Max Size 1M) !',
                    ])
                ],
            ])
            ->add('title',TextType::class)
            ->add('description', TextareaType::class)
            ->add('duration')
            ->add('price', NumberType::class,[
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Your course must have a price !',
                    ]),
                ],
                'invalid_message' => 'Please enter a valid price !',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courses::class,
        ]);
    }
}
