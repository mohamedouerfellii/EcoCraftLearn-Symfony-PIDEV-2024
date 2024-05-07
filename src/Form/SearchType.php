<?php

namespace App\Form;

use App\Service\SearchData;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
            
        $builder->add('contents', TextareaType::class, [ // Change to TextareaType
            'attr' => [
                'placeholder' => 'search...'
            ],
            'required' => false
        ])
        ->add('search', SubmitType::class, [
            'label' => 'Search', // You can customize the label if needed
            'attr' => [
                'class' => 'btn btn-primary' // Add any CSS classes for styling
            ]
        ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}