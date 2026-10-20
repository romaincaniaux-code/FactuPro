<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // ! un champ par propriété, avec son libellé en français
        $builder
            ->add('libelle', TextType::class, ['label' => 'Libellé'])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false])
            ->add('prixHT', NumberType::class, [
                'label' => 'Prix HT (€)',
                'scale' => 2,
                'invalid_message' => 'Veuillez saisir un nombre.',
            ])
            ->add('tauxTva', NumberType::class, [
                'label' => 'Taux de TVA (%)',
                'scale' => 2,
                'invalid_message' => 'Veuillez saisir un nombre.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}