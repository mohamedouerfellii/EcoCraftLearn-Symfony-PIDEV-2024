<?php

namespace App\Form;

use App\Entity\Quizquestions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddQuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class)
            ->add('choice1', TextType::class)
            ->add('choice2', TextType::class)
            ->add('choice3', TextType::class)
            ->add('choice4', TextType::class)
            ->add('correctChoice',ChoiceType::class,[
                'choices' => [
                    'Choice 1' => 'Choice 1',
                    'Choice 2' => 'Choice 2',
                    'Choice 3' => 'Choice 3',
                    'Choice 4' => 'Choice 4',
                ], 
                'multiple' => false, 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quizquestions::class,
        ]);
    }
}
