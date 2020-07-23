<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class AnnonceEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('uri')
            ->add('description')
            ->add('photo', FileType::class, [
                'mapped' => false, 'required' => false, 
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypesMessage' => 'Le fichier est trop lourd',
                    ])
                ],
            ])
            ->add('datePublication')
            ->add('user', EntityType::class, [ 'class' => User::class, 'choice_label' => 'email', 'expanded' => true])
            ->add('categories', EntityType::class, [ 'class' => Categorie::class, 'choice_label' => 'nom', 'expanded' => true, 'multiple' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
