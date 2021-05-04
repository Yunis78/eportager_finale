<?php

namespace App\DataFixtures\ORM;

use App\DataFixtures\ORM\ProductFixtures;
use App\Entity\Categorie;
use App\Entity\product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture implements DependentFixtureInterface
{       
    const DATA = [

        [
            'nom' => 'epices',
        ],
        [
            'nom' => 'Viandes',
        ],
        [
            'nom' => 'Fruits',
        ],
        [
            'nom' => 'Legumes',
        ],
        [
            'nom' => 'Poissons',
        ],
        [
            'nom' => 'Produits laitiers',
        ],
        [
            'nom' => 'Boulangerie',
        ],
        [
            'nom' => 'Miel et confitures',
        ],
        [
            'nom' => 'Condiments',
        ],
    ];
    
    const LABEL = 'categorie-%s';

    public function getDependencies()
    {
        return [ 
            // Fixtures::class
        ];
    }
    
    public function load(ObjectManager $manager)
    {
        foreach( self::DATA as $data){

            //Instabtiate Entities
            $categorie = new Categorie();
            $categorie->setNom($data['nom']);
            
            $manager->persist($categorie);

            $this->setReference(sprintf( self::LABEL , strtolower($data['nom'])), $categorie);
        }
        $manager->flush();
    }

}
