<?php

namespace App\DataFixtures\ORM;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
        const DATA = [

            [
                'email' => 'admin@epotager.com',
                'roles' => ["ROLE_ADMIN"],
                'password' => 'admin1234',
                'givenName' => 'john',
                'familyName' => 'doe',
                'address_street' => 'admin',
                'address_country' => '',
                'address_complement' => '',
                'address_zipcode' => null,
                'address_city' => '',
                'phone' => 0606060060,
                'is_verified' => '',
            ],
            [
                'email' => 'melissa@epotager.com',
                'roles' => ["ROLE_PRODUCER", "ROLE_ADMIN"],
                'password' => 'admin1234',
                'givenName' => 'Melissa',
                'familyName' => 'Pascal',
                'address_street' => '',
                'address_country' => '',
                'address_complement' => '',
                'address_zipcode' => null,
                'address_city' => '',
                'phone' => 0606060060,
                'is_verified' => '',
            ],
            [
                'email' => 'mohammed@epotager.com',
                'roles' => ["ROLE_PRODUCER"],
                'password' => 'admin1234',
                'givenName' => 'Mouhamed',
                'familyName' => 'Hatoum',
                'address_street' => '',
                'address_country' => '',
                'address_complement' => '',
                'address_zipcode' => null,
                'address_city' => '',
                'phone' => 0606060060,
                'is_verified' => '',
            ],
            [
                'email' => 'stephane@epotager.com',
                'roles' => ["ROLE_PRODUCER"],
                'password' => 'admin1234',
                'givenName' => 'stephane',
                'familyName' => 'Jaouen',
                'address_street' => '',
                'address_country' => '',
                'address_complement' => '',
                'address_zipcode' => null,
                'address_city' => '',
                'phone' => 0606060060,
                'is_verified' => '',
            ],
            [
                'email' => 'client@epotager.com',
                'roles' => [""],
                'password' => 'admin1234',
                'givenName' => 'jullie',
                'familyName' => 'gillet',
                'address_street' => '',
                'address_country' => '',
                'address_complement' => '',
                'address_zipcode' => null,
                'address_city' => '',
                'phone' => 0606060060,
                'is_verified' => '',
            ]

        ];

        const LABEL = 'user-%s' ;
        
        /**
         * password Encoder
         * @var UserPasswordEncodeInterface
         */
        private $encoder;

        public function __construct( UserPasswordEncoderInterface $encoder ){

            $this->encoder = $encoder;
        }


    public function load(ObjectManager $manager)
    {
        foreach( self::DATA as $data){

            //Instabtiate Entities
            $user = new User();

            $password = $this->encoder->encodePassword($user, $data['password']);

            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setGivenName($data['givenName']);
            $user->setPassword($password);
            $user->setGivenName($data['givenName']);
            $user->setFamilyName($data['familyName']);
            $user->setAddressStreet($data['address_street']);
            $user->setAddressCountry($data['address_country']);
            $user->setAddressComplement($data['address_complement']);
            $user->setAddressZipcode($data['address_zipcode']);
            $user->setAddressCity($data['address_city']);
            $user->setAddressZipcode($data['address_zipcode']);
            $user->setPhone($data['phone']);
            $user->setIsVerified($data['is_verified']);
            
            $manager->persist($user);

            $this->setReference(sprintf(self::LABEL, strtolower($data['email'])), $user);

        }
        $manager->flush();
    }
}
