<?php declare(strict_types=1);
/*
 * This file is part of sebastian/complexity.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\Complexity;

use Countable;
use IteratorAggregate;
use function count;

/**
 * @psalm-immutable
 */
final class ComplexityCollection implements Countable, IteratorAggregate
{
    /**
     * @psalm-var list<Complexity>
     */
    private $items = [];

    /**
     * @psalm-param list<Complexity> $items
     */
    private function __construct(array $items)
    {
        $this->items = $items;
    }

    public static function fromList(Complexity ...$items): self
    {
        return new self($items);
    }

    /**
     * @psalm-return list<Complexity>
     */
    public function asArray(): array
    {
        return $this->items;
    }

    public function getIterator(): ComplexityCollectionIterator
    {
        return new ComplexityCollectionIterator($this);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function cyclomaticComplexity(): int
    {
        $cyclomaticComplexity = 0;

        foreach ($this as $item) {
            $cyclomaticComplexity += $item->cyclomaticComplexity();
        }

        return $cyclomaticComplexity;
    }
}
