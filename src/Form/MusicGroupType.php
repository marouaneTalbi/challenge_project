<?php

namespace App\Form;

use App\Entity\MusicGroup;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class MusicGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'nom du groupe'
            ])
            // ->add('selected_artists', HiddenType::class, [
            //     'mapped' => false, // Ne pas mapper ce champ à une propriété de l'objet
            //     'attr' => ['id' => 'selected_artists'] // Ajouter un identifiant pour cibler ce champ avec JavaScript
            // ])



            // ->add('artiste', EntityType::class, [
            //     'class' => User::class,
            //     'autocomplete' => true,
            //     'multiple' => true
            // ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MusicGroup::class,
        ]);
    }
}
