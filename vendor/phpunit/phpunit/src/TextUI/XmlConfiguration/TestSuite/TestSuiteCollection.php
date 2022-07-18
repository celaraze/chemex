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
final class TestSuiteCollection implements Countable, IteratorAggregate
{
    /**
     * @var TestSuite[]
     */
    private $testSuites;

    private function __construct(TestSuite ...$testSuites)
    {
        $this->testSuites = $testSuites;
    }

    /**
     * @param TestSuite[] $testSuites
     */
    public static function fromArray(array $testSuites): self
    {
        return new self(...$testSuites);
    }

    /**
     * @return TestSuite[]
     */
    public function asArray(): array
    {
        return $this->testSuites;
    }

    public function getIterator(): TestSuiteCollectionIterator
    {
        return new TestSuiteCollectionIterator($this);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function count(): int
    {
        return count($this->testSuites);
    }
}
