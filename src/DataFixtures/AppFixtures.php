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
            ->setPassword($this->passwordHasher->hashPassword($admin, 'raphael'))
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

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/34384/outside_360/12.jpg?itok=rIr-odQK',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/33832/outside_360/12.jpg?itok=r5ZdwxQ3',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/31329/outside_360/12.jpg?itok=nHPMYXL9',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/34661/outside_360/12.jpg?itok=9JoyG92z',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/33810/outside_360/12.jpg?itok=JS-JxNXe',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/33964/outside_360/12.jpg?itok=utc6da-o',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/32997/outside_360/12.jpg?itok=Pi_vJM6z',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/32465/outside_360/12.jpg?itok=1TyFah4E',

                    'https://soco.be//default/files/styles/gallery_small/public/images/cars-pictures-carlab/33832/outside_360/12.jpg?itok=r5ZdwxQ3',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/33795/outside_360/12.jpg?itok=mlTVAOlc',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/33505/outside_360/12.jpg?itok=q4w3vST7',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures/21da9d03-c7ae-41fb-bbde-37856cc1d5be.jpg?itok=eg_mnQoG',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures/21da9d03-c7ae-41fb-bbde-37856cc1d5be.jpg?itok=eg_mnQoG',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures/21da9d03-c7ae-41fb-bbde-37856cc1d5be.jpg?itok=eg_mnQoG',

                    'https://soco.be/sites/default/files/styles/gallery_small/public/images/cars-pictures-carlab/32059/outside_360/12.jpg?itok=mPjZ3fUx',

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
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/d0606367-eac1-4887-9e89-eeff76f3db38.jpg?itok=IYrTByTT',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27468/outside_360/12.jpg?itok=ZnugdoFU',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/d4554765-ee09-447c-a271-f2521e533972.jpg?itok=9AxmCNBg',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27468/outside_360/2.jpg?itok=S0ujyLy6',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/28039/outside_360/12.jpg?itok=ZUKjjGS1',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/28039/outside_360/5.jpg?itok=Y88r6klA',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27158/outside_360/12.jpg?itok=A6h3K02f',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27158/outside_360/5.jpg?itok=4wVw1PxO',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27882/outside_360/12.jpg?itok=ycdQTu2y',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27882/outside_360/2.jpg?itok=G7dyluEz',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/28063/outside_360/12.jpg?itok=mIDighTy',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/28063/outside_360/2.jpg?itok=Ap67fYSn',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27797/outside_360/12.jpg?itok=omT_7k2o',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27468/outside_360/12.jpg?itok=ZnugdoFU',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/9194b3a1-1d81-498a-b784-5be867e54359.jpg?itok=Pl9xD2b7',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/28215/outside_360/12.jpg?itok=uy7Wb6K6',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/28039/outside_360/12.jpg?itok=ZUKjjGS1'
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
