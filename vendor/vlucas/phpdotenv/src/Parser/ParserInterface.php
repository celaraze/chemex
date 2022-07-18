<?php

declare(strict_types=1);

namespace Dotenv\Parser;

interface ParserInterface
{
    /**
     * Parse content into an entry array.
     *
     * @param string $content
     *
     * @return \Dotenv\Parser\Entry[]
     * @throws \Dotenv\Exception\InvalidFileException
     *
     */
    public function parse(string $content);
}
