<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;


use Symfony\Component\Validator\Constraints as Assert;


class AddEventFormType extends AbstractType
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
                'constraints' => [
                    new NotBlank(['message' => 'Start date cannot be blank']),
                ],
            ])
            ->add('enddate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'End Date',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'End date cannot be blank']),
                ],
            ])




            ->add('attachment', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please upload an image file !']),
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (Max Size 1M) !',
                    ]),
                ],
            ])
            ->add('eventtype')
            ->add('place')
            ->add('placenbr')
            ->add('price');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}