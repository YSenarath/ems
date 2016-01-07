<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/27/2015
 * Time: 10:41 AM
 */

namespace AppBundle\Form\sensor;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class FindSensor extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options )
    {

        $models = $options['models'];
        $types = $options['types'];
        $locations = $options['locations'];

        $builder
            ->add('sensor_id', TextType::class, array('label' => 'Sensor ID'))
            ->add('loc_id', ChoiceType::class, array(
                'label' => 'Location ID' ,
                'required'=>false,
                'expanded' => false ,
                'empty_data'=>null,
                'multiple'=>true ,
                'placeholder'=>'--Select Location to search sensors--',
                'choices' => $locations ))

            ->add('type_name', ChoiceType::class, array(
                'label' => 'Sensor Type',
                'required'=>false,
                'expanded' => true ,
                'multiple'=>true ,
                'empty_data'=>null,
                'placeholder'=>'--Select Type to search Sensors--',
                'choices' => $types ))

            ->add('model_id', ChoiceType::class, array(
                'label' => 'Sensor Model' ,
                'required'=>false,
                'expanded' => false ,
                'empty_data'=>null,
                'multiple'=>true ,
                'placeholder'=>'--Select Model to search Sensors--',
                'choices' => $models ))

            ->add('ins_date', DateType::class, array('label' => 'Installed After'))
            ->add('ins_before', DateType::class, array('label' => 'Installed Before'))
            ->add('submit', SubmitType::class, array('label' => 'Search'));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\sensor\Sensor',
            'models' => null,
            'types' => null,
            'locations' => null,
        ));
    }
}