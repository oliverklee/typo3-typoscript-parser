<?php
namespace Helmich\TypoScriptParser\Tests\Unit\Tokenizer\Printer;

use Helmich\TypoScriptParser\Tokenizer\Printer\StructuredTokenPrinter;
use Helmich\TypoScriptParser\Tokenizer\Token;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class StructuredTokenPrinterTest extends TestCase
{
    /** @var StructuredTokenPrinter */
    private $printer;

    public function setUp(): void
    {
        $this->printer = new StructuredTokenPrinter();
    }

    public function testTokensArePrinted()
    {
        $expectedOutput = <<<OUT
           OBJ_IDENT foo
                  WS ' '
           OP_ASSIGN '='
                  WS ' '
              RVALUE bar
                  WS "\\n"
           OBJ_IDENT bar
                  WS ' '
           OP_ASSIGN '='
                  WS ' '
              RVALUE bar
                  WS "\\n"
OUT;
        $tokens = [
            new Token(Token::TYPE_OBJECT_IDENTIFIER, "foo", 1, 1),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 4),
            new Token(Token::TYPE_OPERATOR_ASSIGNMENT, "=", 1, 5),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 6),
            new Token(Token::TYPE_RIGHTVALUE, "bar", 1, 7),
            new Token(Token::TYPE_WHITESPACE, "\n", 1, 10),
            new Token(Token::TYPE_OBJECT_IDENTIFIER, "bar", 2, 1),
            new Token(Token::TYPE_WHITESPACE, " ", 2, 4),
            new Token(Token::TYPE_OPERATOR_ASSIGNMENT, "=", 2, 5),
            new Token(Token::TYPE_WHITESPACE, " ", 2, 6),
            new Token(Token::TYPE_RIGHTVALUE, "bar", 2, 7),
            new Token(Token::TYPE_WHITESPACE, "\n", 2, 10),
        ];

        $output = $this->printer->printTokenStream($tokens);
        assertThat(trim($output), equalTo(trim($expectedOutput)));
    }
}