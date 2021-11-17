<?php

return [
    'translationsPrefix' => 'admin.',
    'translationsDescriptionsPrefix' => 'pageDescription.',
    'routePrefix' => 'sidecar',
    'routeMiddleware' => ['web', 'auth'],
    'indexLayout' => 'layouts.sidecar',
    'minSearchChars' => 3,
    'reportsPath'   => '\\App\\Reports\\',
    'scripts-stack' => 'edit-scripts',
];
