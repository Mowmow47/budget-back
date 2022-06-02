<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class Fixtures extends Fixture
{
    public function load(ObjectManager $em)
    {
        $loader = new NativeLoader();
        
        $entities = $loader->loadFile(__DIR__ . '/fixtures.yaml')->getObjects();

        foreach ($entities as $entity) {
            $em->persist($entity);
        };

        $em->flush();
    }
}