<?php

namespace App\Form;

use Symfony\Component\Form\{AbstractType,
    Extension\Core\Type\TextType,
    FormBuilderInterface};
use App\Entity\ClientService;
use App\Entity\Product;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class ClientServiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('product', EntityType::class, [
                'constraints' => new NotBlank(),
                'class' => Product::class,
                'choice_value' => 'id',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientService::class
        ]);
    }
}