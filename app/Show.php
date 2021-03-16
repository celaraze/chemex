<?php

namespace App;

use Dcat\Admin\Show\Field;

class Show extends \Dcat\Admin\Show
{
    /**
     * Add a model field to show.
     *
     * @param string $name
     * @param string $label
     *
     * @return Field
     */
    public function field($name, $label = '', $sorts = [])
    {
        return $this->addField($name, $label);
    }
}
