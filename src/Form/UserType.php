<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
              'attr' => [
                'class' => 'form-control',
                'type' => 'email'
              ]
            ])
            ->add('roles',TextType::class,[
              'attr' => [
                'class' => 'form-control',
                'type' => 'text'
              ]
            ])
            ->add('nom',TextType::class,[
              'attr' => [
                'class' => 'form-control',
                'type' => 'text',
              ]
            ])
            ->add('prenom',TextType::class,[
              'attr' => [
                'class' => 'form-control',
                'type' => 'text'
              ]
            ])

        ;
        $builder->get('roles')
             ->addModelTransformer(new CallbackTransformer(
                 function ($json) {
                     // transform the json to a string

                     $str = json_encode($json);
                     $str = strtr($str,array(
                       "["=>"",
                       "]"=>"",
                       "\""=>"",
                     ));
                     return $str;

                 },
                 function ($str) {
                     // transform the string back to an array
                     return explode(',', $str);

                 }
             ))
         ;
    }


    public function onSubmitData(FormEvent $event)
    {
      $user = $event->getData();
      $form = $event->getForm();
$arr=array('ROLE_UN','ROLE_DEUX');
      $user->setNom('MouhDeBoeuf');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
