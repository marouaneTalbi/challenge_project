<?php

namespace App\Form;

use App\Entity\Music;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;

class MusicType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom'
                ]
            ])

            ->add('url', FileType::class, [
                'mapped' => false,
                'label' => 'Fichier (2Mo max)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Url'

                ],
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/mp3',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid mp3 document',
                        'notFoundMessage' => 'Le mp3 n\'a pas été trouvé',
                        'maxSizeMessage' => 'Le mp3 est trop gros ({{ size }} {{ suffix }}). La taille maximum est de  {{ limit }} {{ suffix }}.',
                        'disallowEmptyMessage' => 'Il n\'est pas possible d\'envoyer un fichier vide',
                        'uploadNoFileErrorMessage' => 'Le mp3 n\'a pas été envoyé',
                    ])
                ],
            ])

            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'attr' => [
                    'class' => 'select'
                ],
                'choices' => [
                    'mp3' => 'MP3',
                    'mp4' => 'MP4',

                ],
           
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Music::class,
        ]);
    }
}
