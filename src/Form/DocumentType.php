<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule', FileType::class, array('data_class' => null))
            ->add('commentaire')
            ->add('valider', SubmitType::class)
            ->add('typeDocument2', ChoiceType::class, [
                'choices'  => [
                    'Fiche faisabilité' => 1,
                    'Rapport' => 2,
                    'Poster' => 3,
                    'Fiche d\'appréciation' => 4,
                ],])
            ->add('etudiant', EntityType::class, array(
                'class' => Etudiant::class,
                'choice_label' => function ($personne) {
                    return $personne->getPersonne()->getNom() . ' ' . $personne->getPersonne()->getPrenom();
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}