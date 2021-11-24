<?php

namespace App\DataFixtures;

use App\Constant\Works;
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
        $works = Works::getAll();

        for ($i = 1; $i <= 50; $i++) {
            $cv = new CV();
            $cv->setName($faker->name);
            $cv->setAddress($faker->address);
            $cv->setExperience(rand(1, 100));
            $cv->setEducation('Higher');
            $randKey = array_rand($works);
            $cv->setWork($works[$randKey]);

            $this->entityManager->persist($cv);
        }

        $this->entityManager->flush();
    }
}