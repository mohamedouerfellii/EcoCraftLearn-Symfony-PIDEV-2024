<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('startdate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Start Date',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'required' => true,
            ])
            ->add('enddate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'End Date',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'required' => true,
            ])
            ->add('attachment', FileType::class, [
                'label' => 'Attachment (Image)',
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please upload an image file!',
                    ]),
                    new Assert\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (Max Size 1M)!',
                    ])
                ],
            ])
            ->add('eventtype')
            ->add('place')
            ->add('placenbr')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}