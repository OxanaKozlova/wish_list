<?php

namespace App\Generators;

use App\Repository\WishListRepository;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CSVWishListGenerator
{
    private WishListRepository $wishListRepository;
    private Serializer $serializer;

    public function __construct(WishListRepository $wishListRepository)
    {
        $this->wishListRepository = $wishListRepository;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
    }

    public function generate()
    {
        $wishLists = $this->wishListRepository->getStatistics();
        if (!$wishLists) {
            return '';
        }

        return $this->serializer->encode($wishLists, 'csv');
    }
}
