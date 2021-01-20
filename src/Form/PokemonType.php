<?php

namespace App\Form;

use App\Entity\Pokemon;
use App\Entity\Dresseur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('region')
            ->add('image')
            ->add('dresseur', EntityType::class, [
                'class' => Dresseur::class,
                'choice_label' => 'name'
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}
