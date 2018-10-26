<?php

declare(strict_types=1);

namespace Auth;

use stdClass;

/**
 * @inheritDoc
 *
 * Out of the box, the HAL metadata map is global so if you attempt
 * to use the same class in a metadata map that another module has
 * already configured, you'll get an error due to the duplicate.
 * This works around that issue instead of creating metadata maps
 * isolated by module.
 */
class AuthStdClass extends stdClass
{
}
