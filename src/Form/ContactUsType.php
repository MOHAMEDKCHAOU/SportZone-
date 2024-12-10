<?php
namespace App\Form;

use App\Entity\ContactUs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactUsType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
{
$builder
->add('name', TextType::class, ['label' => 'Your Name'])
->add('email', EmailType::class, ['label' => 'Your Email'])
->add('subject', TextType::class, ['label' => 'Subject'])
->add('message', TextareaType::class, ['label' => 'Message', 'attr' => ['rows' => 4]])
;
}

public function configureOptions(OptionsResolver $resolver): void
{
$resolver->setDefaults([
'data_class' => ContactUs::class,
]);
}
}
