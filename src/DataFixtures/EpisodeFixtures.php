<?php

namespace App\DataFixtures;
use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

//Tout d'abord nous ajoutons la classe Factory de FakerPhp
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();

        /**
         * L'objet $faker que tu récupère est l'outil qui va te permettre
         * de te générer toutes les données que tu souhaites
         */

        for($i = 0; $i < 250; $i++) {
            $episode = new Episode();
            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
            $episodeNumber = $faker->numberBetween(1, 10);
            $episode->setNumber($episodeNumber);
            $episode->setTitle($faker->word());
            $episode->setSynopsis($faker->paragraphs(3, true));
            $episode->setDuration($faker->numberBetween(40, 50));
            $seasonNumber = $faker->numberBetween(1, 24);
            $episode->setSeason($this->getReference('season_'.$seasonNumber));
            $slug = $this->slugger->slug($episode->getTitle());
            $episode->setSlug($slug);
            $manager->persist($episode);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
        ];
    }

}