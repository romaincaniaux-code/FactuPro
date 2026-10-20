<?php

namespace App\Form;

use App\Entity\Remise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemiseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // ! un champ par propriété, avec son libellé en français
        $builder
            ->add('libelle', TextType::class, ['label' => 'Libellé'])
            ->add('taux', NumberType::class, [
                'label' => 'Taux de remise (%)',
                'scale' => 2,
                'invalid_message' => 'Veuillez saisir un nombre.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Remise::class,
        ]);
    }
}