<?php

namespace App\Form;

use App\Entity\ExchangeRate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form to manage the exchange rate manually.
 * Class ExchangeRateType
 * @package App\Form
 */
class ExchangeRateType extends AbstractType
{
   /**
    * Assign fields to a form.
    * @param FormBuilderInterface $builder
    * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('targetCurrency', TextType::class, array(
              'attr' => array(
                'class' => 'form-control form-control-sm'
              ),
              'label' => 'Currency'
            ))
            ->add('rate', TextType::class, array(
              'attr' => array(
                'class' => 'form-control form-control-sm'
              ),
            ))
            ->add('date', DateType::class, array(
              'widget' => 'single_text',
              'attr' => array(
                'class' => 'form-control form-control-sm',
                'html5' => false,
              ),
            ));
    }

   /**
    * Form configuration options.
    * @param OptionsResolver $resolver
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExchangeRate::class,
        ]);
    }
}
