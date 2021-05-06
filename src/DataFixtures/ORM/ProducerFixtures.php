<?php

namespace App\DataFixtures\ORM;

use App\DataFixtures\ORM\UserFixtures;
use App\Entity\Producer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Migrations\Exception\DependencyException;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\VarDumper\Cloner\Data;

class ProducerFixtures extends Fixture implements DependentFixtureInterface
{
    const DATA = [

        [
            'name' => 'Ferme Trubuil Bot',
            'description' => 'Ferme maraîchère au coeur du plateau de Saclay, avec un point de vente ouvert les mercredi, jeudi et vendredi après-midi et le samedi toute la journée.',
            'addresszip' => '78990',
            'villename' => 'Elancourt',
            'siret' => '111111',
            'phone' => '01234567',
            'user' => 'nathalie.trubuil@fake.com',
            'active' => '',
        ],
        [
            'name' => 'Ferme des Beurreries',
            'description' => 'Exploitation céréalière à Feucherolles (78810)',
            'addresszip' => '78990',
            'villename' => 'Elancourt',
            'siret' => '111112',
            'phone' => '01222333',
            'user' => 'paul.delau@fake.com',
            'active' => '',
        ],
        [
            'name' => 'Ferme de Viltain',
            'description' => 'Ferme emblématique du plateau de Saclay connue pour sa cueillette de fruits et légumes de mai à novembre.',
            'addresszip' => '78990',
            'villename' => 'Elancourt',
            'siret' => '111113',
            'phone' => '01333222',
            'user' => 'valentin.leroy@fake.com',
            'active' => '',
        ],
        [
            'name' => 'La Ferme de Romainville et son Garde Manger',
            'description' => 'Ferme habitée par la famille Delalande depuis 5 générations. Elle a ouvert en 2019 un magasin relais de producteurs : le Garde-Manger.',
            'addresszip' => '78990',
            'villename' => 'Elancourt',
            'siret' => '111114',
            'phone' => '01332322',
            'user' => 'melanie.delalande@fake.com',
            'active' => '',
        ]
    ];

    const LABEL = 'producer-%s' ;

    public function getDependencies()
    {
        return [ 
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach( self::DATA as $data){

            $user = $this->getReference(sprintf(UserFixtures::LABEL,\strtolower($data['user'])));
            //Instantiate Entities
            $producer = new Producer();

            $producer->setName($data['name']);
            $producer->setDescription($data['description']);
            $producer->setAddressZip($data['addresszip']);
            $producer->setVilleName($data['villename']);
            $producer->setSiret($data['siret']);
            $producer->setPhone($data['phone']);
            $producer->setActive($data['active']);
            $producer->setUser($user);
            
            $manager->persist($producer);
            $this->setReference(sprintf(self::LABEL, strtolower($data['siret'])), $producer);

        }
        $manager->flush();
    }
}
