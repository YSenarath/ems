<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/12/2015
 * Time: 3:32 AM
 */
// src/AppBundle/Form/UserType.php

namespace AppBundle\Form\security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            'Administrator' => 0,
            'City Mayor' => 1,
            'Technician' => 2,
        );
        $builder
            ->add('id', TextType::class)
            ->add('username', TextType::class)
            ->add('roleId', ChoiceType::class, array(
                'choices'  => $choices, 'choices_as_values' => true,))
            ->add('plainPassword', RepeatedType::class, array(
                    'type' => passwordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ))
            ->add('submit', SubmitType::class, array('label' => 'Register',));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\security\DatabaseUser'
        ));
    }
}