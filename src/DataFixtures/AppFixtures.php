<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        for($i = 1; $i <= 30; $i++) {
            $ad = new Ad();
            $title = $faker->sentence();
            $coverImg = $faker->imageUrl(1000, 400);
            $intro = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $ad->setTitle($title)
                ->setCoverImage($coverImg)
                ->setIntroduction($intro)
                ->setContent($content)
                ->setPrice(mt_rand(35, 230))
                ->setRooms(mt_rand(1, 5));
            for($j = 1; $j <= mt_rand(2, 5); $j++) {
                $img = new Image();
                $img->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($img);
            }
            $manager->persist($ad);
        }
        $manager->flush();
    }
}
