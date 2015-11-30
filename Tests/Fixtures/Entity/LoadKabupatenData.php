<?php

namespace Ais\KabupatenBundle\Tests\Fixtures\Entity;

use Ais\KabupatenBundle\Entity\Kabupaten;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadKabupatenData implements FixtureInterface
{
    static public $kabupatens = array();

    public function load(ObjectManager $manager)
    {
        $kabupaten = new Kabupaten();
        $kabupaten->setTitle('title');
        $kabupaten->setBody('body');

        $manager->persist($kabupaten);
        $manager->flush();

        self::$kabupatens[] = $kabupaten;
    }
}
