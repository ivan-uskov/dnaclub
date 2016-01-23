<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use AppBundle\config\OrderStatus;

class OrderSearchForm extends AbstractType
{
    const CLIENT_SEARCH_FIELD     = 'client';
    const STATUS_SEARCH_FIELD     = 'status';
    const START_DATE_SEARCH_FIELD = 'startDate';
    const END_DATE_SEARCH_FIELD   = 'endDate';

    private static $initData = array();

    public function getName()
    {
        return 'order_search_form';
    }

    public static function getInitData()
    {
        if (!self::$initData)
        {
            self::$initData = array(
                self::CLIENT_SEARCH_FIELD     => '',
                self::STATUS_SEARCH_FIELD     => '',
                self::START_DATE_SEARCH_FIELD => new \DateTime('first day of this month'),
                self::END_DATE_SEARCH_FIELD   => new \DateTime()
            );
        }
        return self::$initData;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var EntityManager $em
         */
        $em = $options['em'];
        $clients = $em->getRepository('AppBundle:Client')->getSortedClients();
        $statusList = OrderStatus::getNames();

        $builder
            ->add(self::CLIENT_SEARCH_FIELD, 'entity', array(
                'label' => 'Клиент',
                'placeholder' => '',
                'class' => 'AppBundle:Client',
                'choice_label' => 'fullName',
                'choices' => $clients,
                'required' => false
            ))
            ->add(self::STATUS_SEARCH_FIELD, 'choice', array(
                'label' => 'Статус',
                'placeholder' => '',
                'choices' => $statusList,
                'choices_as_values' => false,
                'required' => false
            ))
            ->add(self::START_DATE_SEARCH_FIELD, 'date', array(
                'label' => 'Начало',
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker'
                ),
                'required' => false
            ))
            ->add(self::END_DATE_SEARCH_FIELD, 'date', array(
                'label' => 'Конец',
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker'
                ),
                'required' => false
            ))
            ->add('search', 'submit', array(
                'label' => 'Поиск'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'em' => null
        ));
    }
}