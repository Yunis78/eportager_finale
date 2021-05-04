<?php

namespace App\DataFixtures\ORM;

use App\DataFixtures\ORM\ProductFixtures;
use App\Entity\Categorie;
use App\Entity\product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
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
        [
            'nom' => 'Thym',
            'parent' => 'epices',
        ],
        [
            'nom' => 'Sauce Tomate',
            'parent' => 'epices',
        ],
    ];
    
    const LABEL = 'categorie-%s';

    public function load(ObjectManager $manager)
    {
        foreach( self::DATA as $data){

            //Instabtiate Entities
            $categorie = new Categorie();
            $categorie->setNom($data['nom']);
            if (isset($data['parent'])) {
                $categorieParent = $this->getReference(sprintf(CategorieFixtures::LABEL, $data['parent']));
                $categorie->setParent($categorieParent);
            }
            
            $manager->persist($categorie);

            $this->setReference(sprintf( self::LABEL , strtolower($data['nom'])), $categorie);
        }
        $manager->flush();
    }

}
