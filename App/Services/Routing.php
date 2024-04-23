<?php

namespace Services;

final class Routing
{public static function routeComposee(string $route): array
    {
        $routeComposee = ltrim($route, HOME_URL);
        $routeComposee = rtrim($routeComposee, '/');
        $routeComposee = explode('/', $routeComposee);
    
        // Ensure there are at least 2 components (controller and action)
        if (count($routeComposee) < 2) {
            $routeComposee[] = null;
        }
    
        // Ensure there are at least 3 components (controller, action, and parameter)
        if (count($routeComposee) < 3) {
            $routeComposee[] = null;
        }
    
        return $routeComposee;
    }
}
