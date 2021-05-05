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
            'nom' => 'Fruits',
        ],
        [
            'nom' => 'Légumes',
        ],
        [
            'nom' => 'Viandes',
        ],
        [
            'nom' => 'Produits de la mer',
        ],
        [
            'nom' => 'Produits laitiers',
        ],
        [
            'nom' => 'Boulangerie',
        ],
        [
            'nom' => 'Miel et Confitures',
        ],
        [
            'nom' => 'Epices et Condiments',
        ],
        [
            'nom' => 'Thym',
            'parent' => 'epices et condiments',
        ],
        [
            'nom' => 'Sauce Tomate',
            'parent' => 'epices et condiments',
        ],
        [
            'nom' => 'Lait',
            'parent' => 'produits laitiers',
        ],
        [
            'nom' => 'Fromages',
            'parent' => 'produits laitiers',
        ],
        [
            'nom' => 'Miel',
            'parent' => 'miel et confitures',
        ],
        [
            'nom' => 'pain',
            'parent' => 'boulangerie',
        ],
        [
            'nom' => 'poissons',
            'parent' => 'produits de la mer',
        ],
        [
            'nom' => 'viandes rouges',
            'parent' => 'viandes',
        ],
        [
            'nom' => 'courgette',
            'parent' => 'légumes',
        ],
        [
            'nom' => 'pomme',
            'parent' => 'fruits',
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
                $categorieParent = $this->getReference(sprintf(CategorieFixtures::LABEL, strtolower($data['parent'])));
                $categorie->setParent($categorieParent);
            }
            
            $manager->persist($categorie);

            $this->setReference(sprintf( self::LABEL , strtolower($data['nom'])), $categorie);
        }
        $manager->flush();
    }

}
