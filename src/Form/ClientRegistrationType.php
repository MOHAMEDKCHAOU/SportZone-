<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ClientRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Nom is required.']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Nom cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Prénom is required.']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Prénom cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Email is required.']),
                    new Email(['message' => 'Please enter a valid email address.']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Password is required.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Password should be at least {{ limit }} characters long.',
                    ]),
                ],
            ])
            ->add('confirm_password', PasswordType::class, [ // Add confirm_password field
                'mapped' => false, // Don't map this to the entity
                'constraints' => [
                    new NotBlank(['message' => 'Please confirm your password.']),
                ],
            ])
            ->add('adresse', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Adresse is required.']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Adresse cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('telephone', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Téléphone is required.']),
                    new Length([
                        'max' => 15,
                        'maxMessage' => 'Téléphone cannot be longer than {{ limit }} characters.',
                    ]),
                    // Optional: Add regex constraint for phone number format
                    new Regex([
                        'pattern' => '/^\+?[0-9]{1,15}$/', // Adjust pattern as needed
                        'message' => 'Téléphone must be a valid phone number.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
