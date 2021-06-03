<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentType2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule', FileType::class)
            ->add('valider', SubmitType::class)
            ->add('typeDocument2', ChoiceType::class, [
                'choices'  => [
                    'Fiche faisabilité' => 1,
                    'Rapport' => 2,
                    'Poster' => 3,
                    'Fiche d\'appréciation' => 4,
                ],])
            ->add('etudiant', EntityType::class,  array('class' => Etudiant::class,
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('etudiant')
                ->leftJoin('etudiant.personne', 'personne')
                ->addSelect('personne')
                ->where('personne.id = :id')
                ->setParameter('id', $id);
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