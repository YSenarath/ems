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


        $builder
            ->add('sensor_id', SearchType::class, array('label' => 'Sensor ID'));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\sensor\Sensor',

        ));
    }
}