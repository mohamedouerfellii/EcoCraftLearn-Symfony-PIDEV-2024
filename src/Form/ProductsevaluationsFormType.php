<?php

namespace App\Form;

use App\Entity\Productsevaluations;
use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sbyaute\StarRatingBundle\Form\StarRatingType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductsevaluationsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('rate', StarRatingType::class, [
            'label' => 'Rating',
            'stars' => 5, 
            ])
            ->add('commentaire', TextareaType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Productsevaluations::class,
        ]);
    }


    
}
