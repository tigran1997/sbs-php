<?php
/**
 * This is the code which assembles the global configuration for all entry points of the app.
 *
 * Config is being constructed from three parts: base, environment and local configuration overrides,
 * all of this files are in `overrides` subdirectory.
 *
 * NOTE that this global config will be overridden further by the configuration of each individual entry point
 *
 * @see `console/config/main.php`
 * @see `frontend/config/main.php`
 * @see `backend/config/main.php`
 */
define ('SENDGRID_API_KEY','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');



return CMap::mergeArray(
    (require __DIR__ . '/overrides/base.php'),
//    (file_exists(__DIR__ . '/overrides/environment.php') ? require(__DIR__ . '/overrides/environment.php') : array()),
    (file_exists(__DIR__ . '/overrides/local.php') ? require(__DIR__ . '/overrides/local.php') : array())
);

