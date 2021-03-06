<?php

namespace App\DataFixtures\ORM;

use App\DataFixtures\ORM\UserFixtures;
use App\DataFixtures\ORM\ProductFixtures;
use App\DataFixtures\ORM\CategorieFixtures;
use App\DataFixtures\ORM\ProducerFixtures;
use App\Entity\Media;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{       
    const DATA = [

        [
            'product' => 'pomme vert-15-4',
            'path' => 'Pomme1.jpg',
        ],
        [
            'path' => 'courgette.jpg',
            'product' => 'courgette-2-4',
        ],
        [
            'path' => 'viande-de-kobe.jpg',
            'product' => 'the boeuf-2-4',
        ],
        [
            'path' => 'lait.jpg',
            'product' => 'lait-10-4',
        ],
        [
            'path' => 'emmentale.jpg',
            'product' => 'emmental-45-4',
        ],
        [
            'path' => 'miel.jpg',
            'product' => 'miel-20-4',
        ],
        [
            'path' => 'pain_pave.jpg',
            'product' => 'pain pave-0.40-4',
        ],
        [
            'path' => 'thym.jpg',
            'product' => 'thym-25-4',
        ],
        [
            'path' => 'sauce-tomate.jpg',
            'product' => 'sauce tomate-25-4',
        ],
        [
            'path' => 'saumon.jpeg',
            'product' => 'saumon-25-4',
        ],
        [
            'path' => 'epice.jpg',
            'categorie' => 'épices et condiments',
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
            'categorie' => 'légumes',
        ],
        [
            'path' => 'poisson.jpg',
            'categorie' => 'produits de la mer',
        ],
        [
            'path' => 'fromage.jpg',
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
            'categorie' => 'épices et condiments',
        ],
        [
            'path' => 'thym.jpg',
            'categorie' => 'thym',
        ],
        [
            'path' => 'sauce-tomate.jpg',
            'categorie' => 'sauce tomate',
        ],
        [
            'path' => 'profile2.jpg',
            'email' => 'nathalie.trubuil@fake.com',
        ],
        [
            'path' => 'profile2.jpg',
            'email' => 'paul.delau@fake.com',
        ],
        [
            'path' => 'profile2.jpg',
            'email' => 'valentin.leroy@fake.com',
        ],
        [
            'path' => 'profile2.jpg',
            'email' => 'melanie.delalande@fake.com',
        ],
        [
            'path' => 'profile2.jpg',
            'email' => 'client@epotager.com',
        ],
        [
            'path' => 'champ2.jpeg',
            'siret' => '111111', 
        ],
        [
            'path' => 'champ2.jpeg',
            'siret' => '111112',
        ], 
        [
            'path' => 'champ2.jpeg',
            'siret' => '111113',
        ], 
        [
            'path' => 'champ2.jpeg',
            'siret' => '111114',
        ],
        [
            'categorie' => 'Lait',
            'path' => 'lait.jpg',
        ],
        [
            'categorie' => 'Fromages',
            'path' => 'fromage.jpg',
        ],
        [
            'categorie' => 'Miel',
            'path' => 'miel.jpg',
        ],
        [
            'categorie' => 'pain',
            'path' => 'pain.jpg',
        ],
        [
            'categorie' => 'poissons',
            'path' => 'poisson.jpg',
        ],
        [
            'categorie' => 'viandes rouges',
            'path' => 'viande.jpg',
        ],
        [
            'categorie' => 'courgette',
            'path' => 'legume.jpg',
        ],
        [
            'categorie' => 'pomme',
            'path' => 'pomme1.jpg',
        ],
    ];
    
    const LABEL = 'media-%s';

    public function getDependencies()
    {
        return [ 
            ProductFixtures::class,
            CategorieFixtures::class,
            UserFixtures::class,
            ProducerFixtures::class,
        ];
    }
    
    public function load(ObjectManager $manager)
    {
        foreach( self::DATA as $data){

            //Instabtiate Entities
            $media = new Media();

            if (isset($data['product'])) {
                $product = $this->getReference(sprintf(ProductFixtures::LABEL, \strtolower($data['product'])));
                $media->setProduct($product);
            }
            if (isset($data['categorie'])) {
                $categorie = $this->getReference(sprintf(CategorieFixtures::LABEL, \strtolower($data['categorie'])));
                $media->setCategorie($categorie);
            }
            if (isset($data['email'])) {
                $user = $this->getReference(sprintf(UserFixtures::LABEL, \strtolower($data['email'])));
                $media->setUser($user);
            }
            if (isset($data['siret'])) {
                $producer = $this->getReference(sprintf(ProducerFixtures::LABEL, \strtolower($data['siret'])));
                $media->setProducer($producer);
            }

            $media->setPath($data['path']);
            $manager->persist($media);

            $this->setReference(sprintf( self::LABEL , strtolower($data['path'])), $media);
        }
        $manager->flush();
    }

}
