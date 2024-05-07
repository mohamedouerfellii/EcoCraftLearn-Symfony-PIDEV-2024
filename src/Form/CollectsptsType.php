<?php

namespace App\Form;

use App\Entity\Collectspts;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
class CollectsptsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('name')
           
          
           
            ->add('adresse')
            
            
            ->add('image' , FileType::class, array('data_class' => null , 'label'=>"image") )
            ->add('capacity', NumberType::class, [
                'label' => 'Capacity',
                'required' => false, // Modifiez selon vos besoins
                // Autres options du champ...
            ])
           
        ;
         
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Collectspts::class,
        ]);
    }
}
