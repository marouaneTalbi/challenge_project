<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'label' => 'Type',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Type'

                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom'

                ]
            ])
            ->add('size', TextType::class, [
                'label' => 'Size',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Size'

                ]
            ])
            ->add('url', TextType::class, [
                'label' => 'Url',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Url'

                ]
            ])
           // ->add('music_group')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
