<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(SluggerInterface $slugger) {

        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager)
    {
        for ( $i = 1; $i < 6 ; $i++ )  {
            $program = new Program();
            $program->setTitle('La vie est un kiwi'. $i);
            $program->setSynopsis('Lasserre Bixente'. $i);
            $program->setCategory($this->getReference('category_Comédie'));
            $this->addReference('program_'.$i, $program);
           // $program->addActor($this->getReference('actor_'.$i));
            $slug = $this->slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            ];
        // TODO: Implement getDependencies() method.
    }
}