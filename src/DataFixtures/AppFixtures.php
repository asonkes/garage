<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Image;
use App\Entity\Voiture;
use Cocur\Slugify\Slugify;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Fixtures ==> permettent de remplir le contenu de la base de données...
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = FakerFactory::create('fr_FR');
        $slugify = new Slugify();

        for ($i = 1; $i <= 18; $i++) {
            $voiture = new Voiture();

            $voiture->setMarque($faker->randomElement(['Ford', 'Peugeot', 'Opel', 'Fiat', 'Hyundai', 'Volswagen', 'Citroen', 'Dacia', 'Renault', 'Toyota', 'Kia']))
                ->setModele($faker->word)
                ->setCover($faker->randomElement(
                    [
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27408/outside_360/12.jpg?itok=yMOGSljY',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27408/outside_360/11.jpg?itok=wzRbKFZK',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/d0606367-eac1-4887-9e89-eeff76f3db38.jpg?itok=IYrTByTT',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/d4554765-ee09-447c-a271-f2521e533972.jpg?itok=9AxmCNBg',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/25125/outside_360/12.jpg?itok=5dKEEEsY',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/25125/outside_360/11.jpg?itok=XAc4H_-E',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27379/outside_360/12.jpg?itok=A1NMOhyM',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27379/outside_360/11.jpg?itok=Lw5rP_jz',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27379/outside_360/10.jpg?itok=PpJN9Kqh',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/3b9790c4-8f11-42d5-b1d8-e1f96805f1a1.jpg?itok=PDqDoK3Y',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/faf172bb-4c52-44d2-a2d5-114dc075ea98.jpg?itok=X-PEixL8',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures/aeb85dcc-d25e-45a8-b272-cb1fdfd75dbf.jpg?itok=5OoYu21b',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27276/outside_360/12.jpg?itok=1qeEtcJ0',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27276/outside_360/11.jpg?itok=GYU6yXPt',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27276/outside_360/5.jpg?itok=hwvAaK1q',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/26979/outside_360/12.jpg?itok=JTUnF59X',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/26979/outside_360/11.jpg?itok=kwIFY5Rj',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27715/outside_360/12.jpg?itok=lyewFX8N',
                        'https://soco.be/sites/default/files/styles/gallery_big/public/images/cars-pictures-carlab/27715/outside_360/11.jpg?itok=gEePhE5C'
                    ]
                ))
                ->setKm($faker->numberBetween(10000, 400000))
                ->setPrice($faker->numberBetween(10000, 80000))
                ->setNbproprio($faker->numberBetween(1, 6))
                ->setCylindree($faker->randomFloat(2, 1.0, 5.0))
                ->setPuissance($faker->randomNumber(2))
                ->setCarburant($faker->randomElement(['Diesel', 'Essence']))
                ->setDate(new DateTime())
                ->setTransmission($faker->randomElement(['Automatique', 'Manuelle']))
                ->setDescription($faker->sentence)
                ->setTexte($faker->paragraph)
                ->setSlug($slugify->slugify($voiture->getMarque()));
            /**
             * Utilisé pour garder les informations en mémoire, doit être mis dans la boucle, au sinon, ne donne qu'un résultat dans la base de données.
             */
            $manager->persist($voiture);
        }
        /**
         * Utilisé pour effectuer l'enregistrement réel des modifications apportées aux objets de la base de données
         */
        $manager->flush();
    }
}
