<?php

namespace App\Form;

use App\Entity\Producer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProducerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description',TextareaType::class)
            ->add('addresszip')
            ->add('file', CollectionType::class,[
                'entry_type' => MediaType::class,
                "allow_add" => true,
                "allow_delete" => true,
                "prototype" => true,
                'by_reference' => false,
            ])
            ->add('villename', TextType::class)
            ->add('siret')
            ->add('phone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Producer::class,
        ]);
    }
}
