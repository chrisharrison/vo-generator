<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Lexer;

use Yosymfony\ParserUtils\BasicLexer;
use Yosymfony\ParserUtils\LexerInterface;

final class Lexer extends BasicLexer implements LexerInterface
{
    public const T_IDENTIFIER = 'T_IDENTIFIER';
    public const T_BRACE_OPEN = 'T_BRACE_OPEN';
    public const T_BRACE_CLOSE = 'T_BRACE_CLOSE';
    public const T_HOLDS_OPEN = 'T_HOLDS_OPEN';
    public const T_HOLDS_CLOSE = 'T_HOLDS_CLOSE';
    public const T_ASSIGN = 'T_ASSIGN';
    public const T_PARAMS_OPEN = 'T_PARAMS_OPEN';
    public const T_PARAMS_CLOSE = 'T_PARAMS_CLOSE';
    public const T_REQUIRED = 'T_REQUIRED';
    public const T_WHITESPACE = 'T_WHITESPACE';
    public const T_NEWLINE = 'T_NEWLINE';

    public function __construct()
    {
        parent::__construct([
            '/^([a-zA-Z][a-zA-Z0-9]+)/x' => self::T_IDENTIFIER,
            '/^({)/x' => self::T_BRACE_OPEN,
            '/^(})/x' => self::T_BRACE_CLOSE,
            '/^(\[)/x' => self::T_HOLDS_OPEN,
            '/^(\])/x' => self::T_HOLDS_CLOSE,
            '/^(:)/x' => self::T_ASSIGN,
            '/^(\()/x' => self::T_PARAMS_OPEN,
            '/^(\))/x' => self::T_PARAMS_CLOSE,
            '/^(!)/x' => self::T_REQUIRED,
            '/^\h+/x' => self::T_WHITESPACE,
        ]);
        $this->generateNewlineTokens();
        $this->setNewlineTokenName(self::T_NEWLINE);
    }
}
