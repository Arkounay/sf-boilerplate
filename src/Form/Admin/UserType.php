<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserType extends AbstractType
{

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher){}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $creation = $builder->getData()?->getId() === null;

        $builder->add('username');

        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'required' => $creation,
            'first_options' => [
                'label' => 'Mot de passe'
            ],
            'second_options' => [
                'label' => 'Confirmation du mot de passe'
            ],
            'invalid_message' => 'Les deux mots de passes doivent correspondre.',
        ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var User $user */
            $user = $event->getData();
            $password = $event->getForm()->get('plainPassword')->getData();
            if ($password !== null) {
                $encodedPassword = $this->userPasswordHasher->hashPassword($user, $password);
                $user->setPassword($encodedPassword);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}