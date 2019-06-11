<?php

namespace SoftUniBlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Model extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('modelNumber', TextType::class)
            ->add('title', TextType::class)
            ->add('color', TextType::class)
            ->add('price', TextType::class)
            ->add('size', TextType::class)
            ->add('category', TextType::class)
            ->add('description', TextType::class)
            ->add('discount', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SoftUniBlogBundle\Entity\Models',
        ));
    }
}
