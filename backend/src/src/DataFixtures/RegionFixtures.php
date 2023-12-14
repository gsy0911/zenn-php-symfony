<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RegionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 北海道・東北
        $region1 = new Region();
        $region1
            ->setName('北海道・東北')
        ;
        $manager->persist($region1);
        $manager->flush();
        $this->addReference('region1', $region1);

        // 関東
        $region2 = new Region();
        $region2
            ->setName('関東')
        ;
        $manager->persist($region2);
        $manager->flush();
        $this->addReference('region2', $region2);

        // 北陸
        $region3 = new Region();
        $region3
            ->setName('北陸')
        ;
        $manager->persist($region3);
        $manager->flush();
        $this->addReference('region3', $region3);

        // 中部
        $region4 = new Region();
        $region4
            ->setName('中部')
        ;
        $manager->persist($region4);
        $manager->flush();
        $this->addReference('region4', $region4);

        // 関西
        $region5 = new Region();
        $region5
            ->setName('関西')
        ;
        $manager->persist($region5);
        $manager->flush();
        $this->addReference('region5', $region5);

        // 中国
        $region6 = new Region();
        $region6
            ->setName('中国')
        ;
        $manager->persist($region6);
        $manager->flush();
        $this->addReference('region6', $region6);

        // 四国
        $region7 = new Region();
        $region7
            ->setName('四国')
        ;
        $manager->persist($region7);
        $manager->flush();
        $this->addReference('region7', $region7);

        // 九州・沖縄
        $region8 = new Region();
        $region8
            ->setName('九州・沖縄')
        ;
        $manager->persist($region8);
        $manager->flush();
        $this->addReference('region8', $region8);

    }
}
