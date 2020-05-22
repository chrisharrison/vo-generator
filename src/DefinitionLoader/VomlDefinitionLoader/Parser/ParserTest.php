<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Parser;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Lexer\Lexer;
use PHPUnit\Framework\TestCase;
use Yosymfony\ParserUtils\SyntaxErrorException;

final class ParserTest extends TestCase
{
    public function test_schema_is_parsed()
    {
        $string = file_get_contents(__DIR__ . '/test.voml-test');

        $parser = new Parser(new Lexer());

        $expected = new Definitions([
            new Definition([
                'name' => 'Integer',
                'type' => 'integer',
            ]),
            new Definition([
                'name' => 'Composite',
                'type' => 'composite',
                'properties' => [
                    [
                        'name' => 'Enum',
                        'propertyName' => 'enumValue',
                    ],
                    [
                        'name' => 'Integer',
                        'propertyName' => 'integerValue',
                    ],
                ],
            ]),
            new Definition([
                'name' => 'Enum',
                'type' => 'enum',
                'values' => [
                    'ONE',
                    'TWO',
                ],
            ]),
            new Definition([
                'name' => 'Set',
                'type' => 'set',
                'holds' => 'Composite',
            ]),
            new Definition([
                'name' => 'String',
                'type' => 'string',
            ]),
            new Definition([
                'name' => 'Uuid',
                'type' => 'uuid',
            ]),
            new Definition([
                'name' => 'MyEvent',
                'type' => 'message',
                'messageType' => 'event',
                'properties' => [
                    [
                        'name' => 'Uuid',
                        'propertyName' => 'userId',
                        'required' => true,
                    ],
                    [
                        'name' => 'String',
                        'propertyName' => 'notRequired',
                    ],
                    [
                        'name' => 'Integer',
                        'propertyName' => 'arbitraryOptions',
                        'arbitrary' => 'value',
                        'arbitrary2' => 'value2',
                    ],
                ],
            ]),
        ]);

        $parsed = $parser->parse($string);

        $this->assertEquals($expected, $parsed);
    }

    public function test_it_parses_with_an_empty_last_line()
    {
        $string = file_get_contents(__DIR__ . '/with-empty-last-line.voml-test');

        $parser = new Parser(new Lexer());

        $expected = new Definitions([
            new Definition([
                'name' => 'MyInteger',
                'type' => 'integer',
            ]),
        ]);

        $parsed = $parser->parse($string);

        $this->assertEquals($expected, $parsed);
    }

    public function test_it_parses_without_an_empty_last_line()
    {
        $string = file_get_contents(__DIR__ . '/without-empty-last-line.voml-test');

        $parser = new Parser(new Lexer());

        $expected = new Definitions([
            new Definition([
                'name' => 'MyInteger',
                'type' => 'integer',
            ]),
        ]);

        $parsed = $parser->parse($string);

        $this->assertEquals($expected, $parsed);
    }

    public function test_exception_is_thrown_on_invalid_schema()
    {
        $string = file_get_contents(__DIR__ . '/test-invalid.voml-test');
        $parser = new Parser(new Lexer());
        $this->expectException(SyntaxErrorException::class);
        $parser->parse($string);
    }
}
