<?php

namespace App\Form;

use App\Entity\Order;


use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class Orders extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
$builder
->add('name',TextType::class)
->add('surname',TextType::class)
->add('email',TextType::class)
->add('address',TextType::class)
->add('populatedPlace',TextType::class)
->add('office',TextType::class)
->add('phone',TextType::class)
->add('deliver',TextType::class)
->add('orderJson',TextType::class)
->add('wayOfDelivery',TextType::class)
->add('zipCode',TextType::class)
->add('additionalInfo',TextType::class);
}

public function configureOptions(OptionsResolver $resolver)
{
$resolver->setDefaults([
'data_class' => Order::class,
]);
}
}