<?php
namespace App\Form;

use App\Entity\SalleDeSport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class SalleDeSportType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
$builder
->add('nomSalle', TextType::class, [
'attr' => ['class' => 'form-control']
])
->add('adresse', TextType::class, [
'attr' => ['class' => 'form-control']
])
->add('numTel', TelType::class, [
'attr' => ['class' => 'form-control']
])
->add('heureOuverture', TimeType::class, [
'attr' => ['class' => 'form-control']
])
->add('heureFermeture', TimeType::class, [
'attr' => ['class' => 'form-control']
])
->add('image', FileType::class, [
'label' => 'Image de la Salle (jpg, png, jpeg)',
'required' => false, // Make it optional
'constraints' => [
new Image([
'maxSize' => '5M', // Maximum file size (optional)
'mimeTypes' => ['image/jpeg', 'image/png', 'image/jpg'],
'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpeg, png)',
])
],
'attr' => ['class' => 'form-control']
])
;
}

public function configureOptions(OptionsResolver $resolver)
{
$resolver->setDefaults([
'data_class' => SalleDeSport::class,
]);
}
}
