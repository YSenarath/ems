<?php
/**
 * Created by PhpStorm.
 * User: Dulanjaya Tennekoon
 * Date: 1/14/2016
 * Time: 12:28 PM
 */

namespace AppBundle\Form\location;

use Doctrine\DBAL\Types\DecimalType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use AppBundle\Entity\location\Location;

class LocationChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options )
    {
        $areas = $options['areas'];
        $areaId = $options['areaId'];

        $builder
            ->add('id', TextType::class, array('label'=>'ID', 'disabled' => true))
            ->add('address', TextType::class, array('label' => 'Address'))
            ->add('longitude', NumberType::class, array('label' => 'Longitude'))
            ->add('latitude', NumberType::class, array('label' => 'Latitude' ))
            ->add('area_code', ChoiceType::class, array('label' => 'Area','choices'=>$areas,))
            ->add('submit', SubmitType::class, array('label' => 'Save Changes',));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\location\Location',
            'areas' => null,
            'areaId' => null,
        ));
    }
}