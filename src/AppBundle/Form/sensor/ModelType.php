<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/26/2015
 * Time: 5:00 PM
 */

namespace AppBundle\Form\sensor;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use AppBundle\Entity\sensor\Model;

class ModelType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options )
    {
        $type = $options['type'];

        if ($type == 'add'){
            $builder
                ->add('model_id', TextType::class, array('label' => 'Model ID'))
                ->add('manufacture', TextType::class, array('label' => 'Manufacture' ))
                ->add('unit', ChoiceType::class, array('label' => 'Unit' , 'placeholder'=>'--Select a Unit--' ,'choices'=>array('°C'=>'°C' , 'Percentage'=>'%','m/s'=>'m/s', 'Pascal' => 'Pa')))
                ->add('det_range', TextType::class, array('label' => 'Detection Range' ))
                ->add('submit', SubmitType::class, array('label' => 'Save',));
        }
        else if ($type == 'edit'){
            $builder
                ->add('model_id', TextType::class, array('label' => 'Model ID' , 'disabled' => true))
                ->add('manufacture', TextType::class, array('label' => 'Manufacture' ))
                ->add('unit', ChoiceType::class, array('label' => 'Unit' , 'placeholder'=>'--Select a Unit--' ,'choices'=>array('°C'=>'°C' , 'Percentage'=>'%','m/s'=>'m/s', 'Pascal' => 'Pa')))
                ->add('det_range', TextType::class, array('label' => 'Detection Range' ))
                ->add('submit', SubmitType::class, array('label' => 'Save',));
        }


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\sensor\Model',
            'type' => null,


        ));
    }
}