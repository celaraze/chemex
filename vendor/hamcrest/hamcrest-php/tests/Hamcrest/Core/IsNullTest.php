<?php

namespace Hamcrest\Core;

class IsNullTest extends \Hamcrest\AbstractMatcherTest
{

    public function testEvaluatesToTrueIfArgumentIsNull()
    {
        assertThat(null, nullValue());
        assertThat(self::ANY_NON_NULL_ARGUMENT, not(nullValue()));

        assertThat(self::ANY_NON_NULL_ARGUMENT, notNullValue());
        assertThat(null, not(notNullValue()));
    }

    protected function createMatcher()
    {
        return \Hamcrest\Core\IsNull::nullValue();
    }
}
