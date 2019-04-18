<?php

namespace ContractBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContractType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('paymentMethod',ChoiceType::class, ['choices'=> ['visa'=> 'visa', 'mastercard'=> 'mastercard']], array('attr'=>array('myAttr'=>'payment')))
            ->add('startDate',DateType::class,array('attr'=>array('myAttr'=>'start')))
            ->add('finishDate',DateType::class,array('attr'=>array('myAttr'=>'finish')))
            ->add('sum',IntegerType::class,array('attr'=>array('myAttr'=>'sum')))
            ->add('prio',IntegerType::class,array('attr'=>array('myAttr'=>'prio')))
            ->add('idApplication',TextType::class,array('attr'=>array('myAttr'=>'job')))
            ->add('valider',SubmitType::class,array('attr'=>array('myAttr'=>'valid')));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ContractBundle\Entity\Contract'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'Contractbundle_contract';
    }


}
