<?php

declare(strict_types=1);

namespace Dotenv\Store;

interface StoreInterface
{
    /**
     * Read the content of the environment file(s).
     *
     * @return string
     * @throws \Dotenv\Exception\InvalidEncodingException|\Dotenv\Exception\InvalidPathException
     *
     */
    public function read();
}
