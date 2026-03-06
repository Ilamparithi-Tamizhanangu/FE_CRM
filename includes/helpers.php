<?php

declare(strict_types=1);

/**
 * @param array<string, array{label:string, icon:string, description:string}> $modules
 */
function resolveCurrentModule(array $modules): string
{
    $requestedModule = isset($_GET['module']) ? trim((string) $_GET['module']) : 'Home';

    if (!array_key_exists($requestedModule, $modules)) {
        return 'Home';
    }

    return $requestedModule;
}
