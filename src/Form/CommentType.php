<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => false, 'attr' => [
                    'placeholder' => 'Write your comment here...',
                    'class' => 'form-control'  // Ensure it uses Bootstrap's form-control class or similar
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Post Comment',
                'attr' => [
                    'class' => 'btn btn-primary btn-hover-dark',  // Button classes
                    'aria-label' => 'Submit your comment'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}