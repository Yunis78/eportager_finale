<?php

namespace App\DataFixtures\ORM;

use App\DataFixtures\ORM\ProducerFixtures;
use App\DataFixtures\ORM\CategorieFixtures;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{       
    const DATA = [

        [
            'name' => 'pomme vert',
            'stock' => '4',
            'description' => 'pommes juteuses du verger',
            'price' => '15',
            'categorie' => 'pomme',
            'producer' => '111111',
        ],
        [
            'name' => 'courgette',
            'stock' => '4',
            'description' => 'courgette du potager',
            'price' => '2',
            'categorie' => 'courgette',
            'producer' => '111111',
        ],
        [
            'name' => 'The Boeuf',
            'stock' => '4',
            'description' => 'venue tout droit du Limousin',
            'price' => '2',
            'categorie' => 'viandes rouges',
            'producer' => '111112',
        ],
        [
            'name' => 'lait',
            'stock' => '4',
            'description' => 'venue tout droit de la ferme',
            'price' => '10',
            'categorie' => 'lait',
            'producer' => '111113',
        ],
        [
            'name' => 'emmental',
            'stock' => '4',
            'description' => 'fait maison',
            'price' => '45',
            'categorie' => 'fromages',
            'producer' => '111113',
        ],
        [
            'name' => 'Miel',
            'stock' => '4',
            'description' => 'Miel pops',
            'price' => '20',
            'categorie' => 'miel',
            'producer' => '111114',
        ],
        [
            'name' => 'pain pave',
            'stock' => '4',
            'description' => 'le bon pain du pays',
            'price' => '0.40',
            'categorie' => 'pain',
            'producer' => '111113',
        ],
        [
            'name' => 'thym',
            'stock' => '4',
            'description' => 'le bon arôme du pays',
            'price' => '25',
            'categorie' => 'thym',
            'producer' => '111111',
        ],
        [
            'name' => 'sauce tomate',
            'stock' => '4',
            'description' => 'la sauce prefere des tomate',
            'price' => '25',
            'categorie' => 'épices et condiments',
            'producer' => '111114',
        ],
        [
            'name' => 'saumon',
            'stock' => '4',
            'description' => 'le bon poisson des pays du Nord',
            'price' => '25',
            'categorie' => 'poissons',
            'producer' => '111112',
        ]
    ];
    
    const LABEL = 'produit-%s';

    public function getDependencies()
    {
        return [ 
            ProducerFixtures::class,
            CategorieFixtures::class,
        ];
    }
    
    public function load(ObjectManager $manager)
    {
        foreach( self::DATA as $data){

            $producer = $this->getReference(sprintf(ProducerFixtures::LABEL, \strtolower($data['producer'])));
            $categorie = $this->getReference(sprintf(CategorieFixtures::LABEL, \strtolower($data['categorie'])));
            //Instantiate Entities
            $produit = new Product();

            $produit->setName($data['name']);
            $produit->setStock($data['stock']);
            $produit->setDescription($data['description']);
            $produit->setPrice($data['price']);
            $produit->setProducer($producer);
            $produit->setCategorie($categorie);
            
            $manager->persist($produit);
            $this->setReference(sprintf( self::LABEL , strtolower($data['name'].'-'.$data['price'].'-'.$data['stock'])), $produit);
        }
        $manager->flush();
    }

}
