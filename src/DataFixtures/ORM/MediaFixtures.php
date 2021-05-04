<?php

namespace App\DataFixtures\ORM;

use App\DataFixtures\ORM\ProductFixtures;
use App\Entity\Media;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{       
    const DATA = [

        [
            'path' => 'pomme.jpg',
            'product' => 'pomme-15-4',
        ],
        [
            'path' => 'courgette.jpg',
            'product' => 'courgette-2-4',
        ],
        [
            'path' => 'viande-de-kobe.jpg',
            'product' => 'viande de kobe-2-4',
        ],
        [
            'path' => 'lait.jpg',
            'product' => 'lait-10-4',
        ],
        [
            'path' => 'emmentale.jpg',
            'product' => 'emmentale-45-4',
        ],
        [
            'path' => 'miel.jpg',
            'product' => 'miel-20-4',
        ],
        [
            'path' => 'pain_pave.jpg',
            'product' => 'pain pave-0.80-4',
        ],
        [
            'path' => 'thyme.jpg',
            'product' => 'thym-25-4',
        ],
        [
            'path' => 'sauce-tomate.jpg',
            'product' => 'sauce tomate-25-4',
        ],
        [
            'path' => 'saumon.jpg',
            'product' => 'saumon-25-4',
        ],
        [
            'path' => 'epice.jpg',
            'categorie' => 'epices',
        ],
        [
            'path' => 'viande.jpg',
            'categorie' => 'viandes',
        ],
        [
            'path' => 'fruit.jpg',
            'categorie' => 'fruits',
        ],
        [
            'path' => 'legume.jpg',
            'categorie' => 'legumes',
        ],
        [
            'path' => 'poisson.jpg',
            'categorie' => 'poissons',
        ],
        [
            'path' => 'lait.jpg',
            'categorie' => 'produits laitiers',
        ],
        [
            'path' => 'pain.jpg',
            'categorie' => 'boulangerie',
        ],
        [
            'path' => 'miel.jpg',
            'categorie' => 'miel et confitures',
        ],
        [
            'path' => 'thym.jpg',
            'categorie' => 'condiments',
        ],
        [
            'path' => 'thym.jpg',
            'categorie' => 'thym',
        ],
        [
            'path' => 'sauce-tomate.jpg',
            'categorie' => 'sauce tomate',
        ],
    ];
    
    const LABEL = 'media-%s';

    public function getDependencies()
    {
        return [ 
            ProductFixtures::class
        ];
    }
    
    public function load(ObjectManager $manager)
    {
        foreach( self::DATA as $data){

            //Instabtiate Entities
            $media = new Media();

            if (isset($data['product'])) {
                $product = $this->getReference(sprintf(ProductFixtures::LABEL, $data['product']));
                $media->setProduct($product);
            }
            if (isset($data['categorie'])) {
                $categorie = $this->getReference(sprintf(CategorieFixtures::LABEL, $data['categorie']));
                $media->setCategorie($categorie);
            }

            $media->setPath($data['path']);
            $manager->persist($media);

            $this->setReference(sprintf( self::LABEL , strtolower($data['path'])), $media);
        }
        $manager->flush();
    }

}
