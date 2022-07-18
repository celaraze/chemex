<?php

namespace Hamcrest\Text;

/*
 Copyright (c) 2009 hamcrest.org
 */

/**
 * Tests if the argument is a string that contains a substring.
 */
class StringContains extends SubstringMatcher
{

    public function __construct($substring)
    {
        parent::__construct($substring);
    }

    /**
     * Matches if value is a string that contains $substring.
     *
     * @factory
     */
    public static function containsString($substring)
    {
        return new self($substring);
    }

    public function ignoringCase()
    {
        return new StringContainsIgnoringCase($this->_substring);
    }

    // -- Protected Methods

    protected function evalSubstringOf($item)
    {
        return (false !== strpos((string)$item, $this->_substring));
    }

    protected function relationship()
    {
        return 'containing';
    }
}
