<?php

/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/16/2015
 * Time: 9:58 PM
 */

namespace AppBundle\Form\sensor;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;




class SensorType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sensor_id', TextType::class , array('label' => 'Sensor ID'))
            ->add('loc_id', TextType::class , array('label' => 'Location ID'))
            ->add('type_id', TextType::class , array('label' => 'Type'))
            ->add('model_id', TextType::class , array('label' => 'Model'))
            ->add('ins_date', DateType::class , array('label' => 'Installed Date'))
            ->add('t_min', IntegerType::class , array('label' => 'Threshold Min'))
            ->add('t_max', IntegerType::class , array('label' => 'Threshold Max'));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\sensor\Sensor'
        ));
    }
}