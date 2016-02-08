<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreOrderSearchForm extends AbstractType
{
    const IS_RELEASED_SEARCH_FIELD     = 'isReleased';
    const IS_NOT_RELEASED_SEARCH_FIELD = 'isNotReleased';

    public function getName()
    {
        return 'pre_order_search_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add(self::IS_RELEASED_SEARCH_FIELD, 'checkbox', array(
                'label' => 'Выданные'
            ))
            ->add(self::IS_NOT_RELEASED_SEARCH_FIELD, 'checkbox', array(
                'label' => 'Невыданные'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }
}