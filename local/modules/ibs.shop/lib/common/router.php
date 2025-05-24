<?php

namespace Ibs\Shop\Common;

use CComponentEngine;

class Router
{
    public function resolve($component, $sefFolder, $sefUrlTemplates, $variableAliases, &$httpVars): string
    {
        $engine = new CComponentEngine($component);

        $urlTemplates = CComponentEngine::MakeComponentUrlTemplates([], $sefUrlTemplates);

        $aliases = CComponentEngine::MakeComponentVariableAliases([], $variableAliases);

        $page = $engine->ParseComponentPath($sefFolder, $urlTemplates, $httpVars);

        CComponentEngine::initComponentVariables($page, $component, $aliases, $httpVars);

        return $page;
    }
}