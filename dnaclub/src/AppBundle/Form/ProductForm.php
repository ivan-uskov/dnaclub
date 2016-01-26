<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    public function getName()
    {
        return 'product_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Наименование',
                'trim' => true
            ))
            ->add('price', 'money', array(
                'label' => 'Цена',
                'currency' => 'RUB'
            ))
            ->add('pieceName', 'text', array(
                'label' => 'Обозн.',
                'trim' => true
            ))
            ->add('save', 'submit', array(
                'label' => 'Сохранить'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }
}