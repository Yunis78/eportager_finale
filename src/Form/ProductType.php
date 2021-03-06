<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Media;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('file', CollectionType::class,[
            'entry_type' => MediaType::class,
            "allow_add" => true,
            "allow_delete" => true,
            "prototype" => true,
            'by_reference' => false,
            ])
            ->add('name', TextType::class)
            ->add('stock')
            ->add('description',TextareaType::class)
            ->add('price')
            ->add('categorie', EntityType::class,[
                'class' => Categorie::class,
                'choice_label'=>'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
