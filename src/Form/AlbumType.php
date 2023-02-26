<?php

namespace App\Form;
use App\Entity\MusicGroup;
use App\Entity\Album;
use App\Entity\Music;
use App\Repository\AlbumRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\MusicGroupRepository;
use App\Repository\MusicRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $group = $options['musicGroup'];

        $builder
            ->add('name', TextType::class, [
                'label' => 'nom de l\'album',
            ])
            ->add('image', FileType::class,array('data_class' => null, 'required' => false));
            if($options['edit'] == true) {
                $builder->add('music', EntityType::class, [
                    'class' => Music::class,
                    'multiple' => true,
                    'choice_label' => 'name',
                    'attr' => [
                        'class' => 'js-example-basic-multiple'
                    ],
                    'query_builder' => function (MusicRepository $musicRepository) use ($group) {
                        return $musicRepository->findTracksByGroupForEdit($group);
                    },
                ]);
            }
            if($options['edit'] == false) {
                $builder->add('music', EntityType::class, [
                    'class' => Music::class,
                    'multiple' => true,
                    'choice_label' => 'name',
                    'attr' => [
                        'class' => 'js-example-basic-multiple'
                    ],
                    'query_builder' => function (MusicRepository $musicRepository) use ($group) {
                        return $musicRepository->findTracksByGroup($group);
                    },
                ]);
            }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
            'musicGroup' => null,
            'edit' => null
        ]);
    }
}
