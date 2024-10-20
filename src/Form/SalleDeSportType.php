<?php
namespace App\Form;

use App\Entity\SalleDeSport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalleDeSportType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
{
$builder
->add('nomSalle', TextType::class, [
'label' => 'Nom de la salle'
])
->add('adresse', TextType::class, [
'label' => 'Adresse de la salle '
])
->add('numTel', TextType::class, [
'label' => 'Numéro de téléphone'
])
->add('heureOuverture', TimeType::class, [
'label' => 'Heure d\'ouverture'
])
->add('heureFermeture', TimeType::class, [
'label' => 'Heure de fermeture'
]);
}

public function configureOptions(OptionsResolver $resolver): void
{
$resolver->setDefaults([
'data_class' => SalleDeSport::class,
]);
}
}
