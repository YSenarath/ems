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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use AppBundle\Entity\sensor\Model;
use Symfony\Component\Validator\Constraints\DateTime;


class SensorType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options )
    {

        $models = $options['models'];
        $types = $options['types'];
        $locations = $options['locations'];
        $type = $options['type'];


        //print_r($locations);

        if ($type == 'add'){
            $builder
                ->add('sensor_id', TextType::class, array('label' => 'Sensor ID'))
                ->add('loc_id', ChoiceType::class, array(
                    'label' => 'Location ID' ,
                    'placeholder'=>'--Select Location of the Sensor--',
                    'choices' => $locations))

                ->add('type_name', ChoiceType::class,array(
                    'label' => 'Type' ,
                    'placeholder'=>'--Select Type of the Sensor--',
                    'choices' => $types))

                ->add('model_id', ChoiceType::class, array('label' => 'Model' ,
                    'placeholder'=>'--Select Model of the Sensor--',
                    'choices' => $models ))

                ->add('ins_date', DateType::class, array('label' => 'Installed Date',))

                ->add('t_min', TextType::class, array(
                    'label' => 'Threshold Min',
                    'required'=>false,
                    'empty_data'=>null,))

                ->add('t_max', TextType::class, array(
                    'label' => 'Threshold Max',
                    'required'=>false,
                    'empty_data'=>null,))

                ->add('submit', SubmitType::class, array('label' => 'Save'));
        }

        else if ($type == 'edit'){
            $builder
                ->add('sensor_id', TextType::class, array(
                    'label' => 'Sensor ID' ,
                    'disabled' => 'true',
                    ))

                ->add('loc_id', ChoiceType::class, array(
                    'label' => 'Location ID' ,
                    'placeholder'=>'--Select Location of the Sensor--',
                    'choices' => $locations))

                ->add('type_name', ChoiceType::class,array(
                    'label' => 'Type' ,
                    'placeholder'=>'--Select Type of the Sensor--',
                    'choices' => $types))

                ->add('model_id', ChoiceType::class, array('label' => 'Model' ,
                    'placeholder'=>'--Select Model of the Sensor--',
                    'choices' => $models ))

                ->add('ins_date', DateType::class, array('label' => 'Installed Date',))

                ->add('t_min', TextType::class, array(
                    'label' => 'Threshold Min',
                    'required'=>false,
                    'empty_data'=>null,))

                ->add('t_max', TextType::class, array(
                    'label' => 'Threshold Max',
                    'required'=>false,
                    'empty_data'=>null,))

                ->add('submit', SubmitType::class, array('label' => 'Save'));
        }


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\sensor\Sensor',
            'models' => null,
            'types' => null,
            'locations' => null,
            'type' => null,


        ));
    }
}