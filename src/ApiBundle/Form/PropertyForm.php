<?php
namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropertyForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $property, array $options)
    {
        $property->add('type', 'text', array('label' => 'Тип недвижимости:'))
            ->add('room', 'integer', array('label' => 'Кол-во комнат'))
            ->add('price', 'money', array('currency' => 'USD', 'label' => 'Стоимость:'))
            ->add('description', 'textarea', array('label' => 'Описание:'))
            ->add('button', 'submit', array('attr' => array('class' => 'btn btn-save'), 'label' => 'Сохранить объявление'));
    }

    public function getName()
    {
        return 'property';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\Property',
        ));
    }

}
