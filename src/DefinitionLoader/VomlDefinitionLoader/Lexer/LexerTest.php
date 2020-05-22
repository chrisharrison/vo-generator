<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Lexer;

use PHPUnit\Framework\TestCase;
use Yosymfony\ParserUtils\SyntaxErrorException;
use Yosymfony\ParserUtils\Token;
use Yosymfony\ParserUtils\TokenStream;

final class LexerTest extends TestCase
{
    public function test_exception_is_thrown_on_unknown_token()
    {
        $lexer = new Lexer();
        $this->expectException(SyntaxErrorException::class);
        $lexer->tokenize('2invalidIdentifer');
    }

    public function test_it_tokenises_identifiers()
    {
        $lexer = new Lexer();
        $tokenised = $lexer->tokenize('validIdentifier AnotherValid anothervalid2');
        $this->assertEquals([
            Lexer::T_IDENTIFIER,
            Lexer::T_IDENTIFIER,
            Lexer::T_IDENTIFIER,
        ], $this->streamToTokenNames($tokenised));
    }

    public function test_it_tokenises_brace_open()
    {
        $lexer = new Lexer();
        $tokenised = $lexer->tokenize('{');
        $this->assertEquals([
            Lexer::T_BRACE_OPEN,
        ], $this->streamToTokenNames($tokenised));
    }

    public function test_it_tokenises_brace_close()
    {
        $lexer = new Lexer();
        $tokenised = $lexer->tokenize('}');
        $this->assertEquals([
            Lexer::T_BRACE_CLOSE,
        ], $this->streamToTokenNames($tokenised));
    }

    public function test_it_tokenises_holds_open()
    {
        $lexer = new Lexer();
        $tokenised = $lexer->tokenize('[');
        $this->assertEquals([
            Lexer::T_HOLDS_OPEN,
        ], $this->streamToTokenNames($tokenised));
    }

    public function test_it_tokenises_holds_close()
    {
        $lexer = new Lexer();
        $tokenised = $lexer->tokenize(']');
        $this->assertEquals([
            Lexer::T_HOLDS_CLOSE,
        ], $this->streamToTokenNames($tokenised));
    }

    public function test_it_tokenises_assign()
    {
        $lexer = new Lexer();
        $tokenised = $lexer->tokenize(':');
        $this->assertEquals([
            Lexer::T_ASSIGN,
        ], $this->streamToTokenNames($tokenised));
    }

    public function test_it_tokenises_params_open()
    {
        $lexer = new Lexer();
        $tokenised = $lexer->tokenize('(');
        $this->assertEquals([
            Lexer::T_PARAMS_OPEN,
        ], $this->streamToTokenNames($tokenised));
    }

    public function test_it_tokenises_params_close()
    {
        $lexer = new Lexer();
        $tokenised = $lexer->tokenize(')');
        $this->assertEquals([
            Lexer::T_PARAMS_CLOSE,
        ], $this->streamToTokenNames($tokenised));
    }

    public function test_it_tokenises_required()
    {
        $lexer = new Lexer();
        $tokenised = $lexer->tokenize('!');
        $this->assertEquals([
            Lexer::T_REQUIRED,
        ], $this->streamToTokenNames($tokenised));
    }

    private function streamToTokenNames(TokenStream $stream): array
    {
        return array_map(function (Token $token) {
            return $token->getName();
        }, $stream->getAll());
    }
}
