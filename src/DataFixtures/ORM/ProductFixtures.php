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
            'description' => 'pommes juteuses du jargon',
            'price' => '15',
            'categorie' => 'pomme',
            'producer' => '12345',
        ],
        [
            'name' => 'courgette',
            'stock' => '4',
            'description' => 'courgette du jargon',
            'price' => '2',
            'categorie' => 'courgette',
            'producer' => '12345',
        ],
        [
            'name' => 'The Boeuf',
            'stock' => '4',
            'description' => 'venue tout droit du Limousin',
            'price' => '2',
            'categorie' => 'viandes rouges',
            'producer' => '12345',
        ],
        [
            'name' => 'lait',
            'stock' => '4',
            'description' => 'venue tout droit de la ferme',
            'price' => '10',
            'categorie' => 'lait',
            'producer' => '124527',
        ],
        [
            'name' => 'emmental',
            'stock' => '4',
            'description' => 'fait maison',
            'price' => '45',
            'categorie' => 'fromages',
            'producer' => '12345',
        ],
        [
            'name' => 'Miel',
            'stock' => '4',
            'description' => 'Miel pops',
            'price' => '20',
            'categorie' => 'miel',
            'producer' => '124527',
        ],
        [
            'name' => 'pain pave',
            'stock' => '4',
            'description' => 'le bon pain du pays',
            'price' => '0.80',
            'categorie' => 'pain',
            'producer' => '12345',
        ],
        [
            'name' => 'thym',
            'stock' => '4',
            'description' => 'le bon arôme du pays',
            'price' => '25',
            'categorie' => 'thym',
            'producer' => '12452',
        ],
        [
            'name' => 'sauce tomate',
            'stock' => '4',
            'description' => 'la sauce prefere des tomate',
            'price' => '25',
            'categorie' => 'epices & condiments',
            'producer' => '12452',
        ],
        [
            'name' => 'saumon',
            'stock' => '4',
            'description' => 'le bon poissons du pays Nord',
            'price' => '25',
            'categorie' => 'poissons',
            'producer' => '12345',
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

            $producer = $this->getReference(sprintf(ProducerFixtures::LABEL, $data['producer']));
            $categorie = $this->getReference(sprintf(CategorieFixtures::LABEL, $data['categorie']));
            //Instabtiate Entities
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
