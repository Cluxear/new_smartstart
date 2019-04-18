<?php
/**
 * Created by PhpStorm.
 * User: Cluxear
 * Date: 4/14/2019
 * Time: 3:04 PM
 */

namespace CertificationBundle\form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('questions',CollectionType::class,[
                    'entry_type' => TextType::class,
                    'allow_add' => true,
                    'prototype' => true,
                    'prototype_data' => '',
                    'entry_options' => [
                        'attr'=> ['placeholder'=> 'Ajouter une question']
                    ],
                ]
            )
            ->add('image', TextType::class)
            ->add('titre_test', TextType::class)
            ->add('passing_score', IntegerType::class)
            ->add('level', ChoiceType::class,
                ['choices'=> [ 'beginner'=> 'beginner','intermediate'=> 'intermediate','advanced'=> 'advanced',]])
            ->add('summary', TextType::class)
            ->add('success', IntegerType::class)
            ->add('failure', IntegerType::class)
            ->add('cost', IntegerType::class)
            ->add('duration', IntegerType::class)
            ->add('save', SubmitType::class, ['attr'=> ['class' => 'btn btn-primary']])
        ;
    }
}