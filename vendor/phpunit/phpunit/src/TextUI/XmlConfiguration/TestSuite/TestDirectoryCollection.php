<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\TextUI\XmlConfiguration;

use Countable;
use IteratorAggregate;
use function count;

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 * @psalm-immutable
 */
final class TestDirectoryCollection implements Countable, IteratorAggregate
{
    /**
     * @var TestDirectory[]
     */
    private $directories;

    private function __construct(TestDirectory ...$directories)
    {
        $this->directories = $directories;
    }

    /**
     * @param TestDirectory[] $directories
     */
    public static function fromArray(array $directories): self
    {
        return new self(...$directories);
    }

    /**
     * @return TestDirectory[]
     */
    public function asArray(): array
    {
        return $this->directories;
    }

    public function getIterator(): TestDirectoryCollectionIterator
    {
        return new TestDirectoryCollectionIterator($this);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function count(): int
    {
        return count($this->directories);
    }
}
