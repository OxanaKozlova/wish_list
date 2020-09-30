<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ProductFixtures extends Fixture
{
    private Generator $faker;
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->faker = Factory::create();
        $this->productRepository = $productRepository;
    }

    public function load(ObjectManager $manager)
    {
        $productsCount = $this->productRepository->count([]);
        if ($productsCount) {
            return;
        }

        for ($i = 0; $i < 10; ++$i) {
            $product = new Product();
            $product->setName($this->faker->word);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
