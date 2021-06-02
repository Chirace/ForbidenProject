<?php

namespace App\Form;

use App\Entity\Personne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('username')
            ->add('mail')
            ->add('telephone')
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    'Étudiant' => 'Étudiant',
                    'ResponsableUE' => 'ResponsableUE',
                    'Scolarite' => 'Scolarite',
                    'Tuteur' =>'Tuteur',
                    'Administrateur' =>'Administrateur',
                ],])
            ->add('formation', ChoiceType::class, [
                    'choices'  => [
                        'M1 MIAGE' => 1,
                        'M2 MIAGE' => 2,
                    ],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
