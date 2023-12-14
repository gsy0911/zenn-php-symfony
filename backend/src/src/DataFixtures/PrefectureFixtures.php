<?php

namespace App\DataFixtures;

use App\Entity\Prefecture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PrefectureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $prefectures = [
            ['name' => '北海道',  'region' => 'region1' ],  // prefecture1
            ['name' => '青森県',  'region' => 'region1' ],  // prefecture2
            ['name' => '秋田県',  'region' => 'region1' ],  // prefecture3
            ['name' => '山形県',  'region' => 'region1' ],  // prefecture4
            ['name' => '岩手県',  'region' => 'region1' ],  // prefecture5
            ['name' => '宮城県',  'region' => 'region1' ],  // prefecture6
            ['name' => '福島県',  'region' => 'region1' ],  // prefecture7
            ['name' => '東京都',  'region' => 'region2' ],  // prefecture8
            ['name' => '神奈川県','region' => 'region2' ],  // prefecture9
            ['name' => '千葉県',  'region' => 'region2' ],  // prefecture10
            ['name' => '埼玉県',  'region' => 'region2' ],  // prefecture11
            ['name' => '群馬県',  'region' => 'region2' ],  // prefecture12
            ['name' => '栃木県',  'region' => 'region2' ],  // prefecture13
            ['name' => '茨城県',  'region' => 'region2' ],  // prefecture14
            ['name' => '新潟県',  'region' => 'region3' ],  // prefecture15
            ['name' => '石川県',  'region' => 'region3' ],  // prefecture16
            ['name' => '福井県',  'region' => 'region3' ],  // prefecture17
            ['name' => '富山県',  'region' => 'region3' ],  // prefecture18
            ['name' => '愛知県',  'region' => 'region4' ],  // prefecture19
            ['name' => '静岡県',  'region' => 'region4' ],  // prefecture20
            ['name' => '山梨県',  'region' => 'region4' ],  // prefecture21
            ['name' => '長野県',  'region' => 'region4' ],  // prefecture22
            ['name' => '岐阜県',  'region' => 'region4' ],  // prefecture23
            ['name' => '三重県',  'region' => 'region4' ],  // prefecture24
            ['name' => '大阪府',  'region' => 'region5' ],  // prefecture25
            ['name' => '兵庫県',  'region' => 'region5' ],  // prefecture26
            ['name' => '京都府',  'region' => 'region5' ],  // prefecture27
            ['name' => '滋賀県',  'region' => 'region5' ],  // prefecture28
            ['name' => '奈良県',  'region' => 'region5' ],  // prefecture29
            ['name' => '和歌山県','region' => 'region5' ],  // prefecture30
            ['name' => '広島県',  'region' => 'region6' ],  // prefecture31
            ['name' => '岡山県',  'region' => 'region6' ],  // prefecture32
            ['name' => '山口県',  'region' => 'region6' ],  // prefecture33
            ['name' => '鳥取県',  'region' => 'region6' ],  // prefecture34
            ['name' => '島根県',  'region' => 'region6' ],  // prefecture35
            ['name' => '徳島県',  'region' => 'region7' ],  // prefecture36
            ['name' => '香川県',  'region' => 'region7' ],  // prefecture37
            ['name' => '愛媛県',  'region' => 'region7' ],  // prefecture38
            ['name' => '高知県',  'region' => 'region7' ],  // prefecture39
            ['name' => '福岡県',  'region' => 'region8' ],  // prefecture40
            ['name' => '佐賀県',  'region' => 'region8' ],  // prefecture41
            ['name' => '長崎県',  'region' => 'region8' ],  // prefecture42
            ['name' => '熊本県',  'region' => 'region8' ],  // prefecture43
            ['name' => '大分県',  'region' => 'region8' ],  // prefecture44
            ['name' => '宮崎県',  'region' => 'region8' ],  // prefecture45
            ['name' => '鹿児島県','region' => 'region8' ],  // prefecture46
            ['name' => '沖縄県',  'region' => 'region8' ],  // prefecture47
        ];
        foreach($prefectures as $index => $prefectureData) {
            $prefecture = new Prefecture();

            $prefecture
                ->setName($prefectureData['name'])
                ->setRegion($this->getReference($prefectureData['region']))
            ;

            $manager->persist($prefecture);
            $manager->flush();

            $this->addReference('prefecture' . ($index + 1), $prefecture);
        }
    }

    public function getDependencies()
    {
        return [
            RegionFixtures::class,
        ];
    }
}
