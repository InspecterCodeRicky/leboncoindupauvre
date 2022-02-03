<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\Commentaire;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class AnnonceFixtures extends Fixture
{
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $user = new User();
        $user->setEmail('ricky@test.com')
             ->setUsername('Ricky')
             ->setPassword('$2y$13$Y2kitTNmF3ImJYry89JJqO.p.OoOK69orT5ciib2Xy/7X.a7CNtZm')
             ->setRoles([]);
        $manager->persist($user);
        
        for ($a=1; $a <= 4; $a++) { 
            $category = new Category();
            $category->setTitre("Catégorie n°$a");

            $manager->persist($category);

            
            for ($i=1; $i < mt_rand(4,6); $i++) { 
                $annonce = new Annonce();
                $content = '<p>' .join($faker->paragraphs(5)). '</p>';
                $annonce->setTitre($faker->sentence())
                ->setContenu("Contennu de mon annonce n°$i")
                ->setPhoto("https://via.placeholder.com/350x150")
                ->setPrix(45.52)
                ->setAuteur($user)
                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                ->setCategory($category); 
                
                $manager->persist($annonce);
                
                for ($k=0; $k < mt_rand(2,4); $k++) { 
                    $commentaire = new Commentaire();
                    
                    $content = '<p>' .join($faker->paragraphs(5)). '</p>';
                    
                    $interval = (new \DateTime())->diff($annonce->getCreatedAt());
                    $days = $interval->days;

                    $commentaire->setAuteur($faker->name)
                                ->setContent($content)
                                ->setCreatedAt($faker->dateTimeBetween('-' .$days. ' days'))
                                ->setAnnonce($annonce);

                    $manager->persist($commentaire);
                }
            } 
        }
        

        $manager->flush();
    }
}
