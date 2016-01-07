<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 1/7/2016
 * Time: 8:37 AM
 */

namespace AppBundle\Form\security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateEmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employee_id', TextType::class, array('label'=>'ID', 'attr'=>array('readonly'=>true, ), ))
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('NIC', TextType::class, array('label'=>'NIC', ))
            ->add('tel_no', IntegerType::class, array('label'=>'Telephone Number', ))
            ->add('submit', SubmitType::class, array('label' => 'Update', ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\security\Employee'
        ));
    }
}