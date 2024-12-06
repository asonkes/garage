<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Voiture;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Fixtures ==> permettent de remplir le contenu de la base de données...
 */
class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('fr_FR');

        // création d'un admin
        $admin = new User();
        $admin->setFirstName('Audrey')
            ->setLastName('Sonkes')
            ->setEmail('audrey.sonkes@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'ADT93hpk'))
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
            ->setRoles(['ROLE_ADMIN'])
            ->setPicture('');

        $manager->persist($admin);

        // gestion des utilisateurs 
        $users = []; // init d'un tableau pour récup des user pour les annonces
        $genres = ['male', 'femelle'];

        for ($u = 1; $u <= 10; $u++) {
            $user = new User();
            $genre = $faker->randomElement($genres);

            $hash = $this->passwordHasher->hashPassword($user, 'password');

            $user->setFirstname($faker->firstname($genre))
                ->setLastname($faker->lastname())
                ->setEmail($faker->email())
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setPassword($hash)
                ->setPicture('');

            $manager->persist($user);

            $users[] = $user; // ajouter un user au tableau (pour les annonces)

        }

        for ($i = 1; $i <= 18; $i++) {

            $voiture = new Voiture();

            // liaison avec l'user
            $user = $users[rand(0, count($users) - 1)];

            $voiture->setMarque($faker->randomElement(['Ford', 'Peugeot', 'Opel', 'Fiat', 'Hyundai', 'Volswagen', 'Citroen', 'Dacia', 'Renault', 'Toyota', 'Kia']))
                ->setModele($faker->word)
                ->setCover($faker->randomElement([
                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/34483/outside_360/12.jpg?itok=M_RSa5SO',

                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/34661/outside_360/12.jpg?itok=LJdP5qID',

                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/33944/outside_360/12.jpg?itok=M_f8-25K',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/31329/outside_360/12.jpg?itok=nHPMYXL9',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/34661/outside_360/12.jpg?itok=9JoyG92z',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/33810/outside_360/12.jpg?itok=JS-JxNXe',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/33964/outside_360/12.jpg?itok=utc6da-o',

                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/35035/outside_360/12.jpg?itok=Mnde8Re_',

                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/33964/outside_360/12.jpg?itok=1b91cf8p',

                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/32988/outside_360/12.jpg?itok=RF_y3LxS',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/33795/outside_360/12.jpg?itok=mlTVAOlc',

                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/31750/outside_360/12.jpg?itok=vLcFQiPK',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures/21da9d03-c7ae-41fb-bbde-37856cc1d5be.jpg?itok=eg_mnQoG',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures/21da9d03-c7ae-41fb-bbde-37856cc1d5be.jpg?itok=eg_mnQoG',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures/21da9d03-c7ae-41fb-bbde-37856cc1d5be.jpg?itok=eg_mnQoG',

                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/31329/outside_360/12.jpg?itok=JO-ABLpe',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/34326/outside_360/12.jpg?itok=830wVL5y'
                ]))
                ->setKm($faker->numberBetween(10000, 400000))
                ->setPrice($faker->numberBetween(10000, 80000))
                ->setNbproprio($faker->numberBetween(1, 6))
                ->setCylindree($faker->randomFloat(2, 1.0, 5.0))
                ->setPuissance($faker->randomNumber(2))
                ->setCarburant($faker->randomElement(['Diesel', 'Essence']))
                ->setDate($faker->dateTimeBetween(('-3 months')))
                ->setTransmission($faker->randomElement(['Automatique', 'Manuelle']))
                ->setDescription($faker->sentence)
                ->setTexte($faker->paragraph)
                ->setSlug($faker->sentence)
                ->setAuthor($user);
            /**
             * Utilisé pour garder les informations en mémoire, doit être mis dans la boucle, au sinon, ne donne qu'un résultat dans la base de données.
             */
            $manager->persist($voiture);

            for ($j = 1; $j <= 5; $j++) {
                $image = new Image();

                $image->setVoiture($voiture)
                    ->setUrl($faker->randomElement([
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/31886/outside_360/12.jpg?itok=7ujnc7HN',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/31886/outside_360/10.jpg?itok=LxMneql8',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/31886/outside_360/9.jpg?itok=vHJarmak',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/33668/outside_360/12.jpg?itok=LSHHNtlI',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/33668/outside_360/10.jpg?itok=L-Zeg8Ce',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/35072/outside_360/12.jpg?itok=EfSIwYyy',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/35072/details/1732617807-IMG_20241126_114039941_HDR_AE.jpg?itok=BG50YCta',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/35072/details/1732617806-IMG_20241126_114045365_HDR_AE.jpg?itok=Julwrzob',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/35035/outside_360/12.jpg?itok=Mnde8Re_',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/35035/details/1733215263-IMG_20241203_093742003_HDR_AE.jpg?itok=jWzwyXY9',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/35035/details/1733215259-IMG_20241203_093748562_HDR.jpg?itok=pAcpRLbf',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/34483/outside_360/12.jpg?itok=mVKgsKjS',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/34483/details/1730209646-IMG_20241029_144253925_HDR_AE.jpg?itok=Cg9OQucJ',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/34483/details/1730209642-IMG_20241029_144310540_HDR.jpg?itok=lGAOM3YU"',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/34745/outside_360/12.jpg?itok=q8E1qd4w',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/34745/details/1732005095-IMG_20241119_092718759_HDR_AE.jpg?itok=3lvuNtJB',

                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/34745/details/1732005094-IMG_20241119_092724864_HDR_AE.jpg?itok=5ZsUogLB'
                    ]))
                    ->setCaption($faker->sentence);

                /**
                 * Utilisé pour garder les informations en mémoire
                 */
                $manager->persist($image);
            }
        }
        /**
         * Utilisé pour effectuer l'enregistrement réel des modifications apportées aux objets de la base de données
         */
        $manager->flush();
    }
}
