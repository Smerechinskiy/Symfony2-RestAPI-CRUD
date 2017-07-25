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
use ApiBundle\Entity\Realty;

class LoadFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadRealty($manager);
    }

    public function loadRealty($manager)
    {
        $realty = new Realty();
        $realty->setType('Квартира');
        $realty->setRoom(2);
        $realty->setPrice(20000);
        $realty->setDescription('Отличная квартира в новострое (центр города)');
        $manager->persist($realty);

        $realty = new Realty();
        $realty->setType('Коттедж');
        $realty->setRoom(10);
        $realty->setPrice(300000);
        $realty->setDescription('Коттедж в живописном месте у реки');
        $manager->persist($realty);

        $realty = new Realty();
        $realty->setType('Дача');
        $realty->setRoom(1);
        $realty->setPrice(1500);
        $realty->setDescription('Уютная дача с беседкой и мангалом в придачу');
        $manager->persist($realty);

        $manager->flush();
    }
}