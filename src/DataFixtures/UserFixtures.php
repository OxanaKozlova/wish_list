<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepository $userRepository;

    const USERNAME = 'user';
    const PASSWORD = 'password';

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->userRepository->findOneBy(['username' => self::USERNAME]);

        if (!$user) {
            $user = new User();
            $user
                ->setUsername(self::USERNAME)
                ->setRoles(['ROLE_USER']);

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                self::PASSWORD
            ));

            $manager->persist($user);
            $manager->flush();
        }
    }
}
