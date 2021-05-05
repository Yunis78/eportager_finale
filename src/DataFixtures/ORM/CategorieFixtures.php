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
            'nom' => 'Epices & Condiments',
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
            'nom' => 'Produits de mer',
        ],
        [
            'nom' => 'Produits laitiers',
        ],
        [
            'nom' => 'Boulangerie',
        ],
        [
            'nom' => 'Miel & Confitures',
        ],
        [
            'nom' => 'Thym',
            'parent' => 'epices & condiments',
        ],
        [
            'nom' => 'Sauce Tomate',
            'parent' => 'epices & condiments',
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
            'parent' => 'miel & confitures',
        ],
        [
            'nom' => 'pain',
            'parent' => 'boulangerie',
        ],
        [
            'nom' => 'poissons',
            'parent' => 'produits de mer',
        ],
        [
            'nom' => 'viandes rouges',
            'parent' => 'viandes',
        ],
        [
            'nom' => 'courgette',
            'parent' => 'legumes',
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
                $categorieParent = $this->getReference(sprintf(CategorieFixtures::LABEL, $data['parent']));
                $categorie->setParent($categorieParent);
            }
            
            $manager->persist($categorie);

            $this->setReference(sprintf( self::LABEL , strtolower($data['nom'])), $categorie);
        }
        $manager->flush();
    }

}
