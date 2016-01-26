<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use AppBundle\config\SubscriptionType;

class NewSubscriptionForm extends AbstractType
{
    private $client = null;

    public function getName()
    {
        return 'new_subscription_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var EntityManager $em
         */
        $em = $options['em'];
        $isClientPredefined = ($this->client != null);
        if ($isClientPredefined)
        {
            $builder
                ->add('client', 'entity', array(
                    'label' => false,
                    'class' => 'AppBundle:Client',
                    'choice_label' => 'fullName',
                    'choices' => array($this->client),
                    'data' => $this->client,
                    'attr' => array(
                        'class' => 'hidden'
                    )
                ));
        }
        else
        {
            $clients = $em->getRepository('AppBundle:Client')->getSortedClients();
            $builder
                ->add('client', 'entity', array(
                    'label' => 'Клиент',
                    'class' => 'AppBundle:Client',
                    'choice_label' => 'fullName',
                    'choices' => $clients
                ));
        }

        $types = SubscriptionType::getNames();

        $builder
            ->add('date', 'date', array(
                'label' => 'Дата',
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker'
                )
            ))
            ->add('type', 'choice', array(
                'label' => 'Тип подписки',
                'choices' => $types,
                'choices_as_values' => false
            ))
            ->add('count', 'number', array(
                'label' => 'Количество'
            ))
            ->add('save', 'submit', array(
                'label' => 'Добавить подписку'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Subscription',
            'em' => null
        ));
    }

    public function setClient($client)
    {
        $this->client = $client;
    }
}