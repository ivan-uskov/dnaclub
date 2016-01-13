<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthSearchForm extends AbstractType
{
    public function getName()
    {
        return 'month_search_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dates = $options['dates'];

        $builder
            ->add('months', 'choice',
                array('choices' => $dates,
                    'choices_as_values' => false
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'dates' => null
        ));
    }
}