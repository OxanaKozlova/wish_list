<?php

namespace App\Form\Type;

use App\Entity\Product;
use App\Entity\WishList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add(
                'products',
                EntityType::class,
                [
                    'class' => Product::class,
                    'expanded' => true,
                    'multiple' => true,
                    'choice_label' => 'id',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WishList::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'wish_list';
    }
}
