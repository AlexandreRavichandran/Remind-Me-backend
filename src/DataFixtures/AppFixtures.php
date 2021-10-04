<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Music;
use App\Entity\Listing;
use App\Entity\UserBookList;
use App\Entity\UserMovieList;
use App\Entity\UserMusicList;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $movieCategories = ['horror', 'action', 'adventure', 'comedy', 'fantastic', 'drama', 'love'];
        $bookCategories = ['horror', 'action', 'adventure', 'biography', 'self-development', 'financial', 'love'];
        $musicCategories = ['rap', 'rnb', 'classical', 'rock\'n\'roll', 'metal', 'various'];
        $musicTypes = ['Album', 'Song', 'Artist'];

        $movies = [];
        $musics = [];
        $books = [];

        //Creating movies
        for ($i = 0; $i < 10; $i++) {

            $movie = new Movie();
            $movie
                ->setName($faker->word())
                ->setRealisator($faker->firstName() . ' ' . $faker->lastName())
                ->setCategory($faker->randomElement($movieCategories))
                ->setReleasedAt($faker->date('d m Y'));

            $movies[] = $movie;
            $manager->persist($movie);
        }

        //Creating musics
        for ($i = 0; $i < 10; $i++) {

            $music = new Music();
            $music
                ->setType($faker->randomElement($musicTypes))
                ->setName($faker->word())
                ->setCategory($faker->randomElement($musicCategories))
                ->setReleasedAt($faker->date('d m Y'));
            if ($music->getType() === 'Artist') {
                $music->setArtist($faker->firstName . ' ' . $faker->lastName);
            }

            $musics[] = $music;
            $manager->persist($music);
        }

        //Creating books
        for ($i = 0; $i < 10; $i++) {

            $book = new Book();
            $book
                ->setName($faker->word())
                ->setAuthor($faker->firstName . ' ' . $faker->lastName)
                ->setCategory($faker->randomElement($bookCategories))
                ->setReleasedAt($faker->date('d m Y'));

            $books[] = $book;
            $manager->persist($book);
        }

        //Creating users
        for ($i = 0; $i < 5; $i++) {

            $user = new User();
            $user
                ->setPseudonym($faker->firstName . '.' . $faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($this->passwordEncoder->hashPassword($user, 'demo'));

            //Creating listings
            $listing = new Listing();

            //Creating UserMovieLists
            for ($j = 1; $j < mt_rand(2, 6); $j++) {

                $userMovieList = new UserMovieList();
                $userMovieList
                    ->setMovie($faker->randomElement($movies))
                    ->setListOrder($j);
                $manager->persist($userMovieList);
                $listing->addUserMovieList($userMovieList);
            }

            //Creating UserMusicLists
            for ($j = 1; $j < mt_rand(2, 6); $j++) {

                $userMusicList = new UserMusicList();
                $userMusicList
                    ->setMusic($faker->randomElement($musics))
                    ->setListOrder($j);
                $manager->persist($userMusicList);
                $listing->addUserMusicList($userMusicList);
            }

            //Creating UserBookLists
            for ($j = 1; $j < mt_rand(2, 6); $j++) {

                $userBookList = new UserBookList();
                $userBookList
                    ->setBook($faker->randomElement($books))
                    ->setListOrder($j);
                $manager->persist($userBookList);
                $listing->addUserBookList($userBookList);
            }
            $manager->persist($listing);
            $user->setList($listing);
            $manager->persist($user);
        }




        $manager->flush();
    }
}