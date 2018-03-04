<?php

namespace Keboola\OneDriveExtractor;

use Keboola\Component\BaseComponent;

class Component extends BaseComponent
{

    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }

}
