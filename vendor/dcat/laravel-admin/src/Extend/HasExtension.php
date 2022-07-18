<?php

namespace Dcat\Admin\Extend;

trait HasExtension
{
    /**
     * @var ServiceProvider
     */
    protected $extension;

    public function getExtensionName()
    {
        return $this->getExtension()->getName();
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setExtension(ServiceProvider $serviceProvider)
    {
        $this->extension = $serviceProvider;

        return $this;
    }
}
