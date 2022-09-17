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


class EditProduct extends AbstractType
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
            ->add('description',TextType::class, [ 'empty_data' => '' ])
            ->add('isInPromotion',CheckboxType::class, [ 'empty_data' => '' ])
            ->add('isNew',CheckboxType::class, [ 'empty_data' => '' ])
            ->add('inside',TextType::class, [ 'empty_data' => '' ])
            ->add('soleThickness',TextType::class, [ 'empty_data' => null ])
            ->add('shoeHigth',TextType::class, [ 'empty_data' => null ])
            ->add('outside',TextType::class, [ 'empty_data' => '' ])
            ->add('weight',TextType::class, [ 'empty_data' => null ])
            ->add('insole',TextType::class, [ 'empty_data' => '' ])
            ->add('description',TextareaType::class, [ 'empty_data' => '' ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}