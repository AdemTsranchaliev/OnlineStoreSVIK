<?php


namespace App\Form;

use App\Entity\Product;


use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class Products extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class)
            ->add('model',TextType::class)
            ->add('name',TextType::class)
            ->add('color',TextType::class)
            ->add('colorCode',TextType::class)
            ->add('price',TextType::class)
            ->add('category',TextType::class)
            ->add('description',TextType::class)
            ->add('sizes',TextType::class)
            ->add('isInPromotion',CheckboxType::class)
            ->add('isNew',CheckboxType::class)
            ->add('inside',TextType::class)
            ->add('soleThickness',TextType::class)
            ->add('shoeHigth',TextType::class)
            ->add('outside',TextType::class)
            ->add('weight',TextType::class)
            ->add('insole',TextType::class)
            ->add('description',TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}