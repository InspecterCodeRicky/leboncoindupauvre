<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Attachment;
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
        $user->setEmail('test@test.com')
            ->setUsername('Ricky')
            ->setPassword('$2y$13$Y2kitTNmF3ImJYry89JJqO.p.OoOK69orT5ciib2Xy/7X.a7CNtZm')
            ->setRoles([]);
        $manager->persist($user);


        for ($a = 1; $a <= 4; $a++) {
            $category = new Category();
            $category->setTitre("Catégorie n°$a");

            $manager->persist($category);


            for ($i = 1; $i < mt_rand(4, 6); $i++) {
                $annonce = new Annonce();
                $content = '' . join($faker->paragraphs(5));
                $annonce->setTitre("Titre de mon annonce n°$i")
                    ->setContenu($content)
                    ->setPhoto("350x150.png")
                    ->setPrix(150)
                    ->setAuteur($user)
                    ->setSlug($faker->slug)
                    ->setOnSale(true)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);

                $manager->persist($annonce);

                for ($k = 0; $k < mt_rand(1, 2); $k++) {
                    $commentaire = new Commentaire();

                    $content = '<p>' . join($faker->paragraphs(5)) . '</p>';

                    $interval = (new \DateTime())->diff($annonce->getCreatedAt());
                    $days = $interval->days;

                    $commentaire->setAuteur($faker->name)
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                        ->setAnnonce($annonce);

                    $manager->persist($commentaire);
                }

                for ($w = 0; $w < mt_rand(1, 2); $w++) {
                    $galerie = new Attachment();
                    $galerie->setFileName("350x150.png")
                            // ->setFile($faker->file)
                            ->setAnnonce($annonce)
                            ->setFileUpdatedAt(new \DateTime());
                    $manager->persist($galerie);
                }
            }
        }


        $manager->flush();
    }
}
