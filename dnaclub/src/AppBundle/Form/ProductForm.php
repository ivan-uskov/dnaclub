<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Product;

class ProductForm extends AbstractType
{
    public function getName()
    {
        return 'product_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isNew = $options['isNew'];
        $buttonName = $isNew ? 'Добавить' : 'Сохранить';
        $measureTypes = Product::getMeasureTypes();

        $builder
            ->add('name', 'text', array(
                'label' => 'Наименование',
                'trim' => true
            ))
            ->add('price', 'money', array(
                'label' => 'Цена',
                'currency' => 'RUB'
            ))
            ->add('pieceName', 'choice', array(
                'label' => 'Ед.изм.',
                //'placeholder' => '',
                'choices' => $measureTypes,
                'choices_as_values' => false
            ))
            ->add('save', 'submit', array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn-success')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product',
            'isNew' => false
        ));
    }
}