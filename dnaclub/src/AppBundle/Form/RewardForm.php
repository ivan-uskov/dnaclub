<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use AppBundle\utils\DateUtils;
use Symfony\Component\Validator\Constraints\Date;

class RewardForm extends AbstractType
{
    public function getName()
    {
        return 'product_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isNew = $options['isNew'];
        $buttonName = $isNew ? 'Добавить' : 'Сохранить';

        /**
         * @var EntityManager $em
         */
        $em = $options['em'];
        $clients = $em->getRepository('AppBundle:Client')->getSortedClients();

        $builder
            ->add('client', 'entity', array(
                'label' => 'Клиент',
                'class' => 'AppBundle:Client',
                'choice_label' => 'fullName',
                'choices' => $clients,
                'placeholder' => ''
            ))
            ->add('sum', 'money', array(
                'label' => 'Начислено',
                'currency' => 'RUB'
            ))
            ->add('date', 'date', array(
                'label' => 'Дата',
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker'
                )
            ))
            ->add('save', 'submit', array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn-success')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Reward',
            'isNew' => false,
            'em' => null
        ));
    }
}