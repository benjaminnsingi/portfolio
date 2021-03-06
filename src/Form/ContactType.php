<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
         $builder
             ->add('email', EmailType::class, [
                 'label' => 'Votre-email',
                 'attr' => [
                     'class' => 'contact__input'
                 ]
             ])
            ->add('sujet', TextType::class, [
                'label' => 'Sujet du message',
                'attr' => [
                    'class' => 'contact__input'
                ]
            ])
             ->add('message', TextareaType::class,[
                 'attr' => [
                     'class' => 'contact__input',
                     'rows' => 10
                 ]
             ])
         ;
     }

     public function configureOptions(OptionsResolver $resolver)
     {
        $resolver->setDefaults([

        ]);
     }
}