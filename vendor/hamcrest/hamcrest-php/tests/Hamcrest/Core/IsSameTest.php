<?php

namespace Hamcrest\Core;

class IsSameTest extends \Hamcrest\AbstractMatcherTest
{

    public function testEvaluatesToTrueIfArgumentIsReferenceToASpecifiedObject()
    {
        $o1 = new \stdClass();
        $o2 = new \stdClass();

        assertThat($o1, sameInstance($o1));
        assertThat($o2, not(sameInstance($o1)));
    }

    public function testReturnsReadableDescriptionFromToString()
    {
        $this->assertDescription('sameInstance("ARG")', sameInstance('ARG'));
    }

    public function testReturnsReadableDescriptionFromToStringWhenInitialisedWithNull()
    {
        $this->assertDescription('sameInstance(null)', sameInstance(null));
    }

    protected function createMatcher()
    {
        return \Hamcrest\Core\IsSame::sameInstance(new \stdClass());
    }
}
