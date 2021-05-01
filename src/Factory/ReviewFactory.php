<?php

namespace App\Factory;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Review|Proxy createOne(array $attributes = [])
 * @method static Review[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Review|Proxy find($criteria)
 * @method static Review|Proxy findOrCreate(array $attributes)
 * @method static Review|Proxy first(string $sortedField = 'id')
 * @method static Review|Proxy last(string $sortedField = 'id')
 * @method static Review|Proxy random(array $attributes = [])
 * @method static Review|Proxy randomOrCreate(array $attributes = [])
 * @method static Review[]|Proxy[] all()
 * @method static Review[]|Proxy[] findBy(array $attributes)
 * @method static Review[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Review[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ReviewRepository|RepositoryProxy repository()
 * @method Review|Proxy create($attributes = [])
 */
final class ReviewFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'score' => self::faker()->numberBetween(1, 10),
            'comment' => self::faker()->unique()->sentence(),
            'created_date' => self::faker()->dateTimeBetween('-2 years', 'now'),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Review $review) {})
        ;
    }

    protected static function getClass(): string
    {
        return Review::class;
    }
}
