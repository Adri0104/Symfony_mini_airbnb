<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        $adminUser = new User();
        $adminUser->setFirstName('Adrien')
            ->setLastName('Colonna')
            ->setEmail('adri@col.fr')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setPicture('https://avatars.io/twitter/Adri')
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(2)) . '</p>')
            ->addUserRole($adminRole);
        $manager->persist($adminUser);
        $users = [];
        $genres = ['male', 'female'];
        for($i = 0; $i <= 10; $i++) {
            $user = new User();
            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
            $hash = $this->encoder->encodePassword($user, 'password');
            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(2)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);
            $manager->persist($user);
            $users[] = $user;
        }
        for($i = 1; $i <= 30; $i++) {
            $ad = new Ad();
            $title = $faker->sentence();
            $coverImg = $faker->imageUrl(1000, 400);
            $intro = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $user = $users[mt_rand(0, count($users) - 1)];
            $ad->setTitle($title)
                ->setCoverImage($coverImg)
                ->setIntroduction($intro)
                ->setContent($content)
                ->setPrice(mt_rand(35, 230))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);
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
