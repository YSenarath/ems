<?php
/**
 * Created by PhpStorm.
 * User: Dulanjaya Tennekoon
 * Date: 1/4/2016
 * Time: 4:26 PM
 */

namespace AppBundle\Form\location;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use AppBundle\Entity\location\Location;

class LocationType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options )
    {
        $areas = $options['areas'];

        $builder
            ->add('address', TextType::class, array('label' => 'Address'))
            ->add('longitude', TextType::class, array('label' => 'Longitude'))
            ->add('latitude', TextType::class, array('label' => 'Latitude' ))
            ->add('area_code', ChoiceType::class, array('label' => 'Area','choices'=>$areas ))
            ->add('submit', SubmitType::class, array('label' => 'Add',));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\location\Location',
            'areas' => null,
        ));
    }
}