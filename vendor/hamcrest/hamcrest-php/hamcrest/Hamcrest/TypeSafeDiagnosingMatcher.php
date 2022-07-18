<?php

namespace Hamcrest;

/**
 * Convenient base class for Matchers that require a value of a specific type.
 * This simply checks the type and then casts.
 */
abstract class TypeSafeDiagnosingMatcher extends TypeSafeMatcher
{

    final public function matchesSafely($item)
    {
        return $this->matchesSafelyWithDiagnosticDescription($item, new NullDescription());
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     */
    abstract protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription);

    // -- Protected Methods

    final public function describeMismatchSafely($item, Description $mismatchDescription)
    {
        $this->matchesSafelyWithDiagnosticDescription($item, $mismatchDescription);
    }
}
