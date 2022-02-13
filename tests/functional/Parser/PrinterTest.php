<?php

declare(strict_types=1);

namespace Helmich\TypoScriptParser\Tests\Functional\Parser;

use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinter;
use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinterConfiguration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

class PrinterTest extends TestCase
{
    private ASTPrinterInterface $printer;

    public function setUp(): void
    {
        $this->printer = new PrettyPrinter(
            PrettyPrinterConfiguration::create()
                ->withEmptyLineBreaks()
                ->withSpaceIndentation(4)
        );
    }

    public function dataForPrinterTest(): array
    {
        $files = glob(__DIR__ . '/Fixtures/*/*.typoscript');
        $testCases = [];

        foreach ($files as $outputFile) {
            $ast = null;
            $astFile = str_replace('.typoscript', '.php', $outputFile);

            if (file_exists($astFile)) {
                /** @noinspection PhpIncludeInspection */
                $ast = include $astFile;
            }

            $exceptionFile = $outputFile . '.print';
            if (file_exists($exceptionFile)) {
                $outputFile = $exceptionFile;
            }

            $output = file_get_contents($outputFile);

            $testCases[str_replace(".typoscript", "", basename($outputFile))] = [$ast, $output];
        }

        return $testCases;
    }

    /**
     * @dataProvider dataForPrinterTest
     */
    public function testParsedCodeIsCorrectlyPrinted(array $ast, string $expectedOutput): void
    {
        if ($ast === null) {
            $this->markTestIncomplete("no output AST provided");
            return;
        }

        $output = new BufferedOutput();
        $this->printer->printStatements($ast, $output);

        $this->assertEquals(trim($expectedOutput), trim($output->fetch()));
    }
}
