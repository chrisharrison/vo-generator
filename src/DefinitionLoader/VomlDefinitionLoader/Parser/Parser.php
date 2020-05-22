<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Parser;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Lexer\Lexer;
use Throwable;
use Yosymfony\ParserUtils\AbstractParser;
use Yosymfony\ParserUtils\SyntaxErrorException;
use Yosymfony\ParserUtils\Token;
use Yosymfony\ParserUtils\TokenStream;

final class Parser extends AbstractParser
{
    protected function parseImplementation(TokenStream $stream)
    {
        try {
            return $this->parseToDefinitions($stream);
        } catch (Throwable $e) {
            if (
                in_array($e->getMessage(), [
                    'Call to a member function getName() on null',
                    'Call to a member function getValue() on null',
                ])
            ) {
                throw new SyntaxErrorException('Unexpected end of string.');
            }
            throw $e;
        }
    }

    private function parseToDefinitions(TokenStream $stream): Definitions
    {
        $definitions = [];
        while ($stream->hasPendingTokens()) {
            $definition = [];

            $stream->skipWhile(Lexer::T_NEWLINE);

            if ($stream->hasPendingTokens() === false) {
                continue;
            }

            $definition['type'] = $stream->matchNext(Lexer::T_IDENTIFIER);
            $definition['name'] = $stream->matchNext(Lexer::T_IDENTIFIER);

            $definition = array_merge($definition, $this->parseAfterNameDeclaration($stream));
            $definition = array_merge($definition, $this->parseAfterNameDeclaration($stream));
            $definition = array_merge($definition, $this->parseAfterNameDeclaration($stream));

            $definitions[] = new Definition($definition);
        }
        return new Definitions($definitions);
    }

    private function parseAfterNameDeclaration(TokenStream $stream): array
    {
        if ($stream->hasPendingTokens() === false) {
            return [];
        }

        $expectedTokens = [
            Lexer::T_IDENTIFIER,
            Lexer::T_NEWLINE,
            Lexer::T_PARAMS_OPEN,
            Lexer::T_BRACE_OPEN,
            Lexer::T_HOLDS_OPEN,
        ];

        $this->isNextAnyOrException($stream, $expectedTokens);

        if ($stream->isNextAny([Lexer::T_IDENTIFIER, Lexer::T_NEWLINE])) {
            return [];
        }

        $next = $stream->moveNext();

        if ($next->getName() === Lexer::T_PARAMS_OPEN) {
            return $this->parseParams($stream);
        }

        if ($next->getName() === Lexer::T_BRACE_OPEN) {
            return $this->parseBraces($stream);
        }

        if ($next->getName() === Lexer::T_HOLDS_OPEN) {
            return [
                'holds' => $this->parseHolds($stream),
            ];
        }

        return [];
    }

    private function parseBraces(TokenStream $stream): array
    {
        $values = [];
        $properties = [];

        while ($stream->hasPendingTokens()) {
            $stream->skipWhile(Lexer::T_NEWLINE);

            $this->isNextAnyOrException($stream, [Lexer::T_IDENTIFIER, Lexer::T_BRACE_CLOSE]);
            $next = $stream->moveNext();

            if ($next->getName() === Lexer::T_IDENTIFIER) {
                $key = $next->getValue();
            } else {
                break;
            }

            $this->isNextAnyOrException($stream, [Lexer::T_ASSIGN, Lexer::T_NEWLINE, Lexer::T_BRACE_CLOSE]);
            $next = $stream->moveNext();

            if ($next->getName() === Lexer::T_BRACE_CLOSE) {
                break;
            }

            if ($next->getName() === Lexer::T_ASSIGN) {
                $property = [
                    'name' => $stream->matchNext(Lexer::T_IDENTIFIER),
                    'propertyName' => $key,
                ];

                if ($stream->isNext(Lexer::T_REQUIRED)) {
                    $stream->moveNext();
                    $property['required'] = true;
                }

                if ($stream->isNext(Lexer::T_PARAMS_OPEN)) {
                    $stream->moveNext();
                    $property = array_merge($property, $this->parseParams($stream));
                }

                $properties[] = $property;
                continue;
            }

            $values[] = $key;
        }

        $out = [];
        if (count($values)) {
            $out['values'] = $values;
        }
        if (count($properties)) {
            $out['properties'] = $properties;
        }
        return $out;
    }

    private function parseHolds(TokenStream $stream): string
    {
        $holds = $stream->matchNext(Lexer::T_IDENTIFIER);
        $stream->matchNext(Lexer::T_HOLDS_CLOSE);
        return $holds;
    }

    private function parseParams(TokenStream $stream): array
    {
        $keyValues = [];

        while ($stream->hasPendingTokens()) {
            $this->isNextAnyOrException($stream, [Lexer::T_IDENTIFIER, Lexer::T_PARAMS_CLOSE]);
            if ($stream->isNext(Lexer::T_PARAMS_CLOSE)) {
                $stream->moveNext();
                break;
            }

            $key = $stream->matchNext(Lexer::T_IDENTIFIER);
            $stream->matchNext(Lexer::T_ASSIGN);
            $value = $stream->matchNext(Lexer::T_IDENTIFIER);

            $keyValues[$key] = $value;
        }

        return $keyValues;
    }

    private function isNextAnyOrException(TokenStream $stream, array $expectedTokens): void
    {
        if ($stream->isNextAny($expectedTokens) === false) {
            $token = $stream->moveNext() ?? new Token('', 'T_NULL', 0);
            throw new SyntaxErrorException(sprintf(
                'Syntax error: Unexpected "%s" expected token [%s] instead of "%s" at line %s.',
                $token->getValue(),
                implode(', ', $expectedTokens),
                $token->getName(),
                $token->getLine()
            ));
        }
    }
}
