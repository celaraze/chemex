<?php

namespace Hamcrest\Text;

/*
 Copyright (c) 2009 hamcrest.org
 */

use Hamcrest\Description;
use Hamcrest\TypeSafeMatcher;

abstract class SubstringMatcher extends TypeSafeMatcher
{

    protected $_substring;

    public function __construct($substring)
    {
        parent::__construct(self::TYPE_STRING);

        $this->_substring = $substring;
    }

    public function describeTo(Description $description)
    {
        $description->appendText('a string ')
            ->appendText($this->relationship())
            ->appendText(' ')
            ->appendValue($this->_substring);
    }

    abstract protected function relationship();

    protected function matchesSafely($item)
    {
        return $this->evalSubstringOf($item);
    }

    abstract protected function evalSubstringOf($string);

    protected function describeMismatchSafely($item, Description $mismatchDescription)
    {
        $mismatchDescription->appendText('was "')->appendText($item)->appendText('"');
    }
}
