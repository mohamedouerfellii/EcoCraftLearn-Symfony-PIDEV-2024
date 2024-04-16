<?php

namespace App\Form;

use App\Entity\Categoriecodepromo;
use App\Entity\Collectspts;
use App\Entity\Evenement;
use App\Entity\Collects;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormError;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;


use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\ColorType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CollectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('collectDate') 
            
             ->add('collectsptss',EntityType::class,  [
                'class' => Collectspts::class,
                'choice_label' => 'name',
            ])
            
            ->add('materialType', TextType::class, [
                'label' => 'Type de matériel',
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité',
            ])
            
            ;

        // Ajouter un écouteur d'événements pour détecter les changements dans le champ prixd
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Collects::class,
        ]);
    }
}