<?php

declare(strict_types=1);

namespace Stasis\Ext\Twig\Tests;

use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Process\Process;

class StasisTwigExtensionTest extends TestCase
{
    private Process $process;

    public function setUp(): void
    {
        $this->process = new Process([
            dirname(__DIR__, 1) . '/vendor/bin/stasis',
            '--config', __DIR__ . '/source/config.php',
            'generate',
        ]);
    }

    public function testGenerate(): void
    {
        $this->removeDist();
        $this->runGenerateCommand();
        $this->assertContents();
    }

    private function removeDist(): void
    {
        $dir = __DIR__ . '/dist';

        if (!is_dir($dir)) {
            return;
        }

        $iterator = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
        }

        rmdir($dir);
    }

    private function runGenerateCommand(): void
    {
        $this->process->run();
        $exitCode = $this->process->getExitCode();
        $output = $this->process->getErrorOutput();
        $output = $output === '' ? '{empty}' : $output;

        self::assertSame(0, $exitCode, sprintf("Command returned non-zero exit code. Output:\n%s", $output));
    }

    private function assertContents(): void
    {
        $expected = <<<HTML
            <h1>Welcome Home!</h1>
            Home: /
            About: /about
            HTML;

        self::assertFileMatchesFormat(
            $expected,
            __DIR__ . '/dist/index.html',
            'Home: Unexpected page content.',
        );
    }
}
