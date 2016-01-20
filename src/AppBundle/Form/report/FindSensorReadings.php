<?php
namespace AppBundle\Form\report;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class FindSensorReadings extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options )
    {

        $models = $options['models'];
        $types = $options['types'];
        $locations = $options['locations'];

        $builder
            ->add('sensor_id', TextType::class, array(
                'label' => 'Sensor ID',
                'required'=>false,
                'empty_data'=>null,))

            ->add('loc_id', ChoiceType::class, array(
                'label' => 'Location ID' ,
                'required'=>false,
                'expanded' => false ,
                'empty_data'=>null,
                'multiple'=>true ,
                'placeholder'=>'--Select Location to search sensor readings--',
                'choices' => $locations ))

            ->add('type_name', ChoiceType::class, array(
                'label' => 'Sensor Type',
                'required'=>false,
                'expanded' => true ,
                'multiple'=>true ,
                'empty_data'=>null,
                'placeholder'=>'--Select Type to search Sensor readings--',
                'choices' => $types ))

            ->add('model_id', ChoiceType::class, array(
                'label' => 'Sensor Model' ,
                'required'=>false,
                'expanded' => false ,
                'empty_data'=>null,
                'multiple'=>true ,
                'placeholder'=>'--Select Model to search Sensor readings--',
                'choices' => $models ))

            ->add('ins_date', DateType::class, array('label' => 'Installed After'))
            ->add('ins_before', DateType::class, array('label' => 'Installed Before'))

//            Sensor filter
            ->add(
                'noOfReadings',
                IntegerType::class,
                array('attr' => array('label' => 'Max. readings per sensor','min' => 1, 'max' => 1000))
            )
            ->add(
                'startDate',
                DateTimeType ::class,
                array(
                    'input' => 'datetime',
                    'date_widget' => "choice",
                    'time_widget' => "choice",
                )
            )
            ->add(
                'endDate',
                DateTimeType::class,
                array(
                    'input' => 'datetime',
                    'date_widget' => "choice",
                    'time_widget' => "choice",
                )
            )

            ->add('submit', SubmitType::class, array('label' => 'Search' , 'attr'=> array('class'=> 'btn-default glyphicon glyphicon-search')));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\report\SensorReadingFilter',
            'models' => null,
            'types' => null,
            'locations' => null,
        ));
    }
}