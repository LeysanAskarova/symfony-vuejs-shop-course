<?php


namespace App\Form\Handler;


use App\Entity\User;
use App\Utils\Manager\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFormHandler
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserManager $userManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userManager = $userManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param Form $form
     * @return User
     */
    public function processEditForm(Form $form)
    {
        $plainPassword = $form->get('plainPassword')->getData();
        $newEmail = $form->get('newEmail')->getData();
        /** @var User $user */
        $user = $form->getData();

        if(!$user->getId()) {
            $user->setEmail($newEmail);
        }
        if($plainPassword) {
            $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodedPassword);
        }
        $this->userManager->save($user);
        return $user;
    }


}