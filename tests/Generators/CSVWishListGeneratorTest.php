<?php


namespace App\Tests\Generators;

use App\Generators\CSVWishListGenerator;
use App\Repository\WishListRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CSVWishListGeneratorTest extends TestCase
{
    private WishListRepository $wishListRepository;
    private Serializer $serializer;

    public function setUp(): void
    {
        $this->wishListRepository = $this->createMock(WishListRepository::class);
        $this->serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
    }

    public function testGenerate()
    {
        $wishListData = [
            [
                "title" => "title_1",
                "username" => "username_1",
                "products" => "3",
            ],
            [
                "title" => "title_2",
                "username" => "username_2",
                "products" => "1",
            ]
        ];

        $this->wishListRepository
            ->method('getStatistics')
            ->will($this->onConsecutiveCalls([], $wishListData));

        $generator = new CSVWishListGenerator($this->wishListRepository);
        $this->assertEquals('', $generator->generate());

        $generator = new CSVWishListGenerator($this->wishListRepository);
        $csvResult = $generator->generate();
        $decodedResult = $this->serializer->decode($csvResult, 'csv');

        $this->assertSameSize($wishListData, $decodedResult);
        foreach($wishListData as $key => $value) {
            $this->assertEquals($wishListData[$key], $decodedResult[$key]);
        }
    }
}