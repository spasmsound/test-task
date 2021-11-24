<?php

namespace App\DataFixtures;

use App\Entity\CV;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CVFixtures extends Fixture
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $cv = new CV();
            $cv->setName($faker->name);
            $cv->setAddress($faker->address);
            $cv->setExperience('Big');
            $cv->setEducation('Higher');
            $cv->setWork($faker->company);

            $this->entityManager->persist($cv);
        }

        $this->entityManager->flush();
    }
}