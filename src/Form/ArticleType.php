<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ Titre personnalisé
            ->add('titre', TextType::class, [
                'label' => 'Titre de l’article',
                'attr' => [
                    'placeholder' => 'Ex: Les nouveautés de Symfony...',
                    'class' => 'form-control shadow-sm',
                ],
            ])
            
            // Champ Contenu personnalisé
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu détaillé',
                'attr' => [
                    'rows' => 10,
                    'placeholder' => 'Rédigez le corps de votre article ici...',
                    'class' => 'form-control shadow-sm',
                ],
            ])
            
            // Champ Auteur personnalisé
            ->add('auteur', TextType::class, [
                'label' => 'Nom de l’auteur',
                'attr' => [
                    'placeholder' => 'Ex: Jean Dupont',
                    'class' => 'form-control shadow-sm',
                ],
            ])

            // Champ Catégorie (Relation Doctrine)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'required' => false,
                'attr' => ['class' => 'form-control shadow-sm'],
            ])
            
            // Champ Date de création personnalisé
            ->add('dateCreation', DateTimeType::class, [
                'label' => 'Date et heure de publication',
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'attr' => [
                    'class' => 'form-control shadow-sm'
                ],
            ])
            
            // Champ Booléen (Publié) personnalisé
            ->add('publie', CheckboxType::class, [
                'label' => 'Rendre cet article visible sur le site',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label_attr' => [
                    'class' => 'form-check-label fw-bold'
                ],
                'row_attr' => [
                    'class' => 'form-check form-switch my-3'
                ],
            ])
            
            // Bouton de soumission personnalisé
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Publier l’article',
                'attr' => [
                    'class' => 'btn btn-success btn-lg w-100 shadow'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}