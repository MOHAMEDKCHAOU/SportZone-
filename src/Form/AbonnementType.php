<?php
namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\SalleDeSport;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'abonnement',
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'abonnement',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Entrez la description de l\'abonnement',
                    'rows' => 5,
                ],
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix (€)',
                'currency' => 'EUR',
                'attr' => [
                    'placeholder' => 'Entrez le prix de l\'abonnement',
                ],
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'Sélectionnez la date de début',
                ],
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'Sélectionnez la date de fin',
                ],
            ])
            ->add('salle', EntityType::class, [
                'class' => SalleDeSport::class,
                'choice_label' => 'nomSalle',
                'label' => 'Salle de Sport',
                'placeholder' => 'Choisissez une salle de sport',
                'required' => false,
                'choices' => $options['salles'],
            ])
            ->add('services', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'nom',
                'label' => 'Services associés',
                'multiple' => true,
                'expanded' => true, // To display checkboxes
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
            'salles' => [], // Pass dynamically from the controller
        ]);
    }
}
