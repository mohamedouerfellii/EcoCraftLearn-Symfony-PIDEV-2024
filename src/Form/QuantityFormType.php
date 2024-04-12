<?php

namespace App\Form;

use App\Entity\Souscarts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class QuantityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $productQuantity = $options['product_quantity'];

        $builder
            ->add('quantiteproduct', IntegerType::class, [
                'label' => 'Quantity', 
                'error_bubbling' => false, // Set error_bubbling to false
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => $productQuantity,
                        'message' => 'La quantité sélectionnée est supérieure à la quantité disponible.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('product_quantity');
        $resolver->setDefaults([
            'data_class' => Souscarts::class,
        ]);
    }
}
