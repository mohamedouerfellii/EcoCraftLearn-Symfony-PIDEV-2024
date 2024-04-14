<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Please enter your first name',
                ]),
                new Assert\Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => 'Your first name should have at least {{ limit }} characters',
                    'maxMessage' => 'Your first name should have at most {{ limit }} characters',
                ]),
            ],
            'attr' => [
                'placeholder' => 'First Name',
                'class' => 'formbold-form-input',
                'id' => 'firstname',
            ],
        ])
        ->add('lastname', TextType::class, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Please enter your last name',
                ]),
                new Assert\Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => 'Your last name should have at least {{ limit }} characters',
                    'maxMessage' => 'Your last name should have at most {{ limit }} characters',
                ]),
            ],
            'attr' => [
                'placeholder' => 'Last Name',
                'class' => 'formbold-form-input',
                'id' => 'lastname',
            ],
        ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your email address',
                    ]),
                    new Assert\Email([
                        'message' => 'The email "{{ value }}" is not a valid email address.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'formbold-form-input',
                    'id' => 'email',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options' => [
                    'attr' => [
                        'label' => false,
                        'placeholder' => 'Password'
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'label' => false,
                        'placeholder' => 'Confirm Password'
                    ]
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a Password',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                    ]),
                ],
            ])
            
            ->add('numtel', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your phone number',
                    ]),
                    // Ajoutez d'autres contraintes de validation selon vos besoins
                ],
                'attr' => [
                    'placeholder' => 'Phone Number',
                    'class' => 'formbold-form-input',
                    'id' => 'numtel',
                ],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'admin',
                    'User' => 'user',
                    // Ajoutez plus d'options de rôle au besoin
                ],
                'placeholder' => 'Choose a role',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please choose a role',
                    ]),
                ],
                'attr' => [
                    'class' => 'formbold-form-input',
                    'id' => 'role',
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                    // Ajoutez plus d'options de genre au besoin
                ],
                'placeholder' => 'Choose a gender',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please choose a gender',
                    ]),
                ],
                'attr' => [
                    'class' => 'formbold-form-input',
                    'id' => 'gender',
                ],
            ])
            ->add('image',FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please upload an image file !',
                    ]),
                    new Assert\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (Max Size 1M) !',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
   
}
