<?php
/**
 * Created by PhpStorm.
 * User: Богдан
 * Date: 25.07.2017
 * Time: 23:28
 */

namespace ApiBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ApiBundle\Entity\Property;

class LoadFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadProperty($manager);
    }

    public function loadProperty($manager)
    {
        $property = new Property();
        $property->setType('Квартира');
        $property->setRoom(2);
        $property->setPrice(20000);
        $property->setDescription('Отличная квартира в новострое (центр города)');
        $manager->persist($property);

        $property = new Property();
        $property->setType('Коттедж');
        $property->setRoom(10);
        $property->setPrice(300000);
        $property->setDescription('Коттедж в живописном месте у реки');
        $manager->persist($property);

        $property = new Property();
        $property->setType('Дача');
        $property->setRoom(1);
        $property->setPrice(1500);
        $property->setDescription('Уютная дача с беседкой и мангалом в придачу');
        $manager->persist($property);

        $manager->flush();
    }
}