<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('Fr_fr');

        //Créer 3 Cat"gories Fakée
        for($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph(3));
            $manager->persist($category);


            for ($j = 1; $j <= mt_rand(4,6); $j++) {
                $article = new Article();

                $content = '<p>'.join($faker->paragraph(5), '</p><p>'.'</p>');

                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);

                $manager->persist($article);

                for ($k = 1; $k <= mt_rand(4,10); $k++) {
                    $comment = new Comment();

                    $content = '<p>'.join($faker->paragraph(2), '</p><p>'.'</p>');




                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt();
                }
            }
        }
        $manager->flush();
    }
}
