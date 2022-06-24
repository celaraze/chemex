<?php
/**
 * Copyright Andreas Heigl <andreas@heigl.org>
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace StellaMaris\Clock;

use DateTimeImmutable;

interface ClockInterface
{
    /**
     * Return the current point in time as a DateTimeImmutable object
     */
    public function now(): DateTimeImmutable;
}