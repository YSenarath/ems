<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/26/2015
 * Time: 5:00 PM
 */

namespace AppBundle\Form\sensor;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use AppBundle\Entity\sensor\Model;

class ErrorType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options )
    {

        $builder
            ->add('sensor_id', TextType::class, array('label' => 'Sensor ID' , 'disabled' => true))
            ->add('report_id', TextType::class, array('label' => 'Report ID' , 'disabled' => true))
            ->add('error_desc', TextareaType::class, array('label' => 'Error Description' ))
            ->add('is_fixed', CheckboxType::class, array(
                'label' => 'Fixed',
                'required'=>false,

                ))
            ->add('submit', SubmitType::class, array('label' => 'Set',));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\sensor\SensorError',



        ));
    }
}