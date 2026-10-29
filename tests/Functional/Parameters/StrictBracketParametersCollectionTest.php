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

namespace ApiPlatform\Tests\Functional\Parameters;

use ApiPlatform\Test\ApiTestCase;
use ApiPlatform\Tests\Fixtures\TestBundle\ApiResource\StrictBracketParametersCollection;
use ApiPlatform\Tests\SetupClassResourcesTrait;
use PHPUnit\Framework\Attributes\DataProvider;

final class StrictBracketParametersCollectionTest extends ApiTestCase
{
    use SetupClassResourcesTrait;

    protected static ?bool $alwaysBootKernel = false;

    /**
     * @return class-string[]
     */
    public static function getResources(): array
    {
        return [StrictBracketParametersCollection::class];
    }

    public static function acceptedQueries(): iterable
    {
        yield 'bracketed key' => ['exists[name]=1'];
        yield 'url-encoded bracketed key' => ['exists%5Bname%5D=0'];
        yield 'expanded :property template' => ['order[name]=asc&order[id]=desc'];
        yield 'list on a plain key' => ['status[]=active&status[]=pending'];
    }

    #[DataProvider('acceptedQueries')]
    public function testDeclaredBracketedParametersAreAllowed(string $query): void
    {
        self::createClient()->request('GET', 'strict_bracket_query_parameters_collection?'.$query);
        $this->assertResponseStatusCodeSame(200);
    }

    public static function rejectedQueries(): iterable
    {
        yield 'undeclared sub-key' => ['exists[id]=1', 'exists[id]'];
        yield 'sub-key outside the template properties' => ['order[unknown]=asc', 'order[unknown]'];
        yield 'scalar on a bracketed key' => ['exists=1', 'exists'];
        yield 'unknown top-level key' => ['unknown=1', 'unknown'];
    }

    #[DataProvider('rejectedQueries')]
    public function testUndeclaredParametersAreRejected(string $query, string $parameter): void
    {
        self::createClient()->request('GET', 'strict_bracket_query_parameters_collection?'.$query);
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains(['detail' => \sprintf('Parameter "%s" not supported', $parameter)]);
    }
}
