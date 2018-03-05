<?php

namespace Keboola\OneDriveExtractor;

use Keboola\Component\Config\BaseConfigDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class ConfigDefinition extends BaseConfigDefinition
{

    /**
     * @return ArrayNodeDefinition|NodeDefinition
     */
    protected function getParametersDefinition()
    {
        $parametersNode = parent::getParametersDefinition();

        $parametersNode
            ->isRequired()
            ->children()
                ->scalarNode('id')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('output')
                    ->isRequired()
                ->end()
            ->end();

        return $parametersNode;
    }

}
