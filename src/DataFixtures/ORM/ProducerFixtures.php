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
            'name' => 'la ferme de l\'orient',
            'description' => 'zeboi',
            'siret' => '12345',
            'addresszip' => '78990',
            'villename' => 'elancourt',
            'phone' => '0154548654',
            'user' => 'mouhamed@epotager.com',
            'active' => '',
        ],
        [
            'name' => 'la ferme du gali',
            'description' => 'zeboi',
            'addresszip' => '78990',
            'villename' => 'elancourt',
            'siret' => '124527',
            'phone' => '015454856',
            'user' => 'melissa@epotager.com',
            'active' => '',
        ],
        [
            'name' => 'la ferme du galis',
            'description' => 'zeboi',
            'addresszip' => '78990',
            'villename' => 'elancourt',
            'siret' => '12452',
            'phone' => '015454856',
            'user' => 'stephane@epotager.com',
            'active' => '',
        ],
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

            $user = $this->getReference(sprintf(UserFixtures::LABEL,$data['user']));
            //Instabtiate Entities
            $producer = new Producer();

            $producer->setName($data['name']);
            $producer->setDescription($data['description']);
            $producer->setAddressZip($data['addresszip']);
            $producer->setVilleName($data['villename']);
            $producer->setSiret($data['siret']);
            $producer->setPhone($data['phone']);
            $producer->setActive($data['active']);
            
            $manager->persist($producer);

            $this->setReference(sprintf(self::LABEL, strtolower($data['siret'])), $producer);

        }
        $manager->flush();
    }
}
