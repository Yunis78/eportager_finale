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
                'roles' => ["ROLE_ADMIN"],
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
                'roles' => ["ROLE_ADMIN"],
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
                'roles' => ["ROLE_ADMIN"],
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
                'roles' => ["ROLE_USER"],
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
            ],
            [
                'email' => 'nathalie.trubuil@fake.com',
                'roles' => ["ROLE_PRODUCER"],
                'password' => 'nathalie1234',
                'givenName' => 'Nathalie',
                'familyName' => 'Trubuil',
                'address_street' => 'Route de Vauhallan',
                'address_country' => 'France',
                'address_complement' => '',
                'address_zipcode' => 91400,
                'address_city' => 'Saclay',
                'phone' => 0606060060,
                'is_verified' => '',
            ],
            [
                'email' => 'paul.delau@fake.com',
                'roles' => ["ROLE_PRODUCER"],
                'password' => 'paul1234',
                'givenName' => 'Paul',
                'familyName' => 'Delau',
                'address_street' => 'Route de Rivière',
                'address_country' => 'France',
                'address_complement' => '',
                'address_zipcode' => 78200,
                'address_city' => 'Mantes-la-Jolie',
                'phone' => 0606060060,
                'is_verified' => '',
            ],
            [
                'email' => 'valentin.leroy@fake.com',
                'roles' => ["ROLE_PRODUCER"],
                'password' => 'valentin1234',
                'givenName' => 'Valentin',
                'familyName' => 'Leroy',
                'address_street' => '8 Avenue de Bures',
                'address_country' => 'France',
                'address_complement' => '',
                'address_zipcode' => 91400,
                'address_city' => 'Orsay',
                'phone' => 0606060060,
                'is_verified' => '',
            ],
            [
                'email' => 'melanie.delalande@fake.com',
                'roles' => ["ROLE_PRODUCER"],
                'password' => 'melanie1234',
                'givenName' => 'Mélanie',
                'familyName' => 'Delalande',
                'address_street' => '20 rue Antoine Lemaistre',
                'address_country' => 'France',
                'address_complement' => '',
                'address_zipcode' => 78114,
                'address_city' => 'Magny-les-Hameaux',
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
