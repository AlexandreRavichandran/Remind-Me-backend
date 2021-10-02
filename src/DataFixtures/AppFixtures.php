<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Music;
use App\Entity\Listing;
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

        for ($i = 0; $i < 10; $i++) {

            $movie = new Movie();
            $movie
                ->setName($faker->word())
                ->setRealisator($faker->firstName() . ' ' . $faker->lastName())
                ->setCategory($faker->randomElement($movieCategories))
                ->setReleasedAt($faker->date('d m Y'));

            $manager->persist($movie);
        }

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

            $manager->persist($music);
        }

        for ($i = 0; $i < 10; $i++) {

            $book = new Book();
            $book
                ->setName($faker->word())
                ->setAuthor($faker->firstName . ' ' . $faker->lastName)
                ->setCategory($faker->randomElement($bookCategories))
                ->setReleasedAt($faker->date('d m Y'));

            $manager->persist($book);
        }

        for ($i = 0; $i < 5; $i++) {

            $user = new User();
            $user
                ->setPseudonym($faker->firstName . '.' . $faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($this->passwordEncoder->hashPassword($user, 'demo'))
                ->setList(new Listing);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
