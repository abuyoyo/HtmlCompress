<?php declare(strict_types=1);

namespace WyriHaximus\HtmlCompress\Tests;

use Generator;
use WyriHaximus\HtmlCompress\Compressor\HtmlCompressor;
use WyriHaximus\TestUtilities\TestCase;
use function array_pop;
use function assert;
use function explode;
use function file_get_contents;
use function glob;
use function is_string;
use const DIRECTORY_SEPARATOR;
use const GLOB_ONLYDIR;

/**
 * @internal
 */
final class HtmlMinObserverTest extends TestCase
{
    /**
     * @return Generator<array<int, string>>
     */
    public function providerEdgeCase(): Generator
    {
        $items = glob(__DIR__ . DIRECTORY_SEPARATOR . 'HtmlMinObserver' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            $itemName = explode(DIRECTORY_SEPARATOR, $item);
            $itemName = array_pop($itemName);

            yield $itemName => [$item . DIRECTORY_SEPARATOR];
        }
    }

    /**
     * @param mixed $dir
     *
     * @dataProvider providerEdgeCase
     */
    public function testEdgeCase($dir): void
    {
        $in = file_get_contents($dir . 'in.html');
        assert(is_string($in));
        $out = file_get_contents($dir . 'out.html');
        assert(is_string($out));

        $compressor = require $dir . 'compressor.php';
        assert($compressor instanceof HtmlCompressor);
        $result = $compressor->compress($in);

        self::assertSame($out, $result);
    }
}
