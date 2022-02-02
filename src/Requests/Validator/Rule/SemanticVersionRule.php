<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Validator\Rule;

use Rakit\Validation\Rule;

class SemanticVersionRule extends Rule
{
    /**
     * Semantic versioning regex.
     * @see https://semver.org/
     * @see https://regex101.com/r/Ly7O1x/3/
     */
    public const SEMVER_VERSION_REGEX = '/^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)\.(?P<patch>0|[1-9]\d*)(?:-(?P<prerelease>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+(?P<buildmetadata>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';

    public function check($value): bool
    {
        return (is_string($value) && preg_match(self::SEMVER_VERSION_REGEX, $value));
    }
}
