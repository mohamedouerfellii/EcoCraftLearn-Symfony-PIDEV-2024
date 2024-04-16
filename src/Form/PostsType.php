<?php

namespace App\Form;

use App\Entity\Posts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {$builder
    ->add('content', TextareaType::class, [
        'label' => false, 
        'attr' => [
            'placeholder' => 'What\'s on your mind?',
            'rows' => 3,
            
        ],
    ])
    ->add('attachment', FileType::class, [
        'label' => false, 
        'mapped' => false,
        'required' => false,
       
            
              
        
    ])
    ->add('Add', SubmitType::class, [
        
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}