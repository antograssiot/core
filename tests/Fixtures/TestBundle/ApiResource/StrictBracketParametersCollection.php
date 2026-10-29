<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatform\Tests\Fixtures\TestBundle\ApiResource;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;

#[GetCollection(
    uriTemplate: 'strict_bracket_query_parameters_collection',
    strictQueryParameterValidation: true,
    parameters: [
        'exists[name]' => new QueryParameter(),
        'order[:property]' => new QueryParameter(properties: ['id', 'name']),
        'status' => new QueryParameter(),
    ],
    provider: [self::class, 'provider']
)]
class StrictBracketParametersCollection
{
    public string $id = '1';

    public ?string $name = null;

    public static function provider(): array
    {
        return [new self()];
    }
}
