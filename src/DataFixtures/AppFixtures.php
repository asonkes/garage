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
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/d0606367-eac1-4887-9e89-eeff76f3db38.jpg?itok=IYrTByTT',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/d4554765-ee09-447c-a271-f2521e533972.jpg?itok=9AxmCNBg',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/25125/outside_360/12.jpg?itok=5dKEEEsY',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/25125/outside_360/11.jpg?itok=XAc4H_-E',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27276/outside_360/12.jpg?itok=1qeEtcJ0',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27276/outside_360/11.jpg?itok=GYU6yXPt',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27276/outside_360/5.jpg?itok=hwvAaK1q',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/26979/outside_360/12.jpg?itok=JTUnF59X',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/c7f95e1c-ba59-4525-8ba4-adc0b0febfff.jpg?itok=soQZHu6u',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/26996/outside_360/12.jpg?itok=6SzTEvVJ',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/42be8bd8-2031-48f8-add9-7c3dc4717d54.jpg?itok=O2-Bj64E',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/8259215a-aa6c-45ea-ae06-55e9e2b0aba1.jpg?itok=TYSp3xj4',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/26840/outside_360/12.jpg?itok=BKJJ_jXa',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27279/outside_360/12.jpg?itok=EIv9zp-z',
                    'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27797/outside_360/12.jpg?itok=omT_7k2o'
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
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/d0606367-eac1-4887-9e89-eeff76f3db38.jpg?itok=IYrTByTT',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/d4554765-ee09-447c-a271-f2521e533972.jpg?itok=9AxmCNBg',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/25125/outside_360/12.jpg?itok=5dKEEEsY',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/ 25125/outside_360/11.jpg?itok=XAc4H_-E',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27379/outside_360/11.jpg?itok=Lw5rP_jz',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27276/outside_360/12.jpg?itok=1qeEtcJ0',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27276/outside_360/11.jpg?itok=GYU6yXPt',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27276/outside_360/5.jpg?itok=hwvAaK1q',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/26979/outside_360/12.jpg?itok=JTUnF59X',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/c7f95e1c-ba59-4525-8ba4-adc0b0febfff.jpg?itok=soQZHu6u',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/26996/outside_360/12.jpg?itok=6SzTEvVJ',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/42be8bd8-2031-48f8-add9-7c3dc4717d54.jpg?itok=O2-Bj64E',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/8259215a-aa6c-45ea-ae06-55e9e2b0aba1.jpg?itok=TYSp3xj4',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/26840/outside_360/12.jpg?itok=BKJJ_jXa',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27279/outside_360/12.jpg?itok=EIv9zp-z',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27797/outside_360/12.jpg?itok=omT_7k2o'
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
