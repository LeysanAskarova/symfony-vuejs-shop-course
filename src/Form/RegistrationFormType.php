<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Enter your email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'autofocus' => 'autofocus',
                    'placeholder' => 'Please enter your email'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please fill the field'
                    ]),
                    new Email([
                        'message' => 'Please enter a valid e-mail'
                    ])
                ]

            ])
            ->add('agreeTerms', CheckboxType::class, [
                #'label' => 'I agree to the <a href="#">privacy policy</a> *', for symfony 5.*
                'label' => 'I agree to the privacy policy',
                'required' => true,
                # 'label_html' => true, for symfony 5.*
                'attr' => [
                    'class' => 'custom-control-input'
                ],
                'label_attr' => [
                    'class' => 'custom-control-label'
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Please check the box',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Enter you password',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'new-password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
