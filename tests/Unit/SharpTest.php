<?php

namespace Kiatng\Sharp\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Kiatng\Sharp\Sharp;

class SharpTest extends TestCase
{
    protected string $testOutputDir;
    protected string $testSvgFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testOutputDir = __DIR__ . '/../fixtures/tmp';
        $this->testSvgFile = __DIR__ . '/../fixtures/test-image.svg';

        if (!is_dir($this->testOutputDir)) {
            mkdir($this->testOutputDir, 0777, true);
        }
    }

    public function testThrowsExceptionForInvalidInput()
    {
        $this->expectException(\Exception::class);

        $io = [
            'input' => [
                'is_raw' => false,
                'data' => 'non-existent-file.jpg'
            ],
            'output' => [
                'is_raw' => true
            ]
        ];

        Sharp::run($io, []);
    }

    public function testCanConvertSvgToPng()
    {
        $outputPath = $this->testOutputDir . '/converted.png';

        $io = [
            'input' => [
                'is_raw' => false,
                'data' => $this->testSvgFile
            ],
            'output' => [
                'is_raw' => false,
                'file' => $outputPath
            ]
        ];

        Sharp::run($io, []);

        $this->assertFileExists($outputPath);
    }

    public function testCanFlipImage()
    {
        $outputPath = $this->testOutputDir . '/flipped.jpg';

        $io = [
            'input' => ['is_raw' => false, 'data' => $this->testSvgFile],
            'output' => ['is_raw' => false, 'file' => $outputPath]
        ];

        $params = [
            'flip' => []
        ];

        Sharp::run($io, $params);

        $this->assertFileExists($outputPath);
    }

    public function testCanGrayscaleImage()
    {
        $outputPath = $this->testOutputDir . '/grayscale.gif';

        $io = [
            'input' => ['is_raw' => false, 'data' => $this->testSvgFile],
            'output' => ['is_raw' => false, 'file' => $outputPath]
        ];

        $params = [
            'grayscale' => []
        ];

        Sharp::run($io, $params);

        $this->assertFileExists($outputPath);
    }

    public function testCanRemoveAlphaChannel()
    {
        $outputPath = $this->testOutputDir . '/removed-alpha.jpg';

        $io = [
            'input' => ['is_raw' => true, 'data' => file_get_contents($this->testSvgFile), 'ext' => 'svg'],
            'output' => ['is_raw' => false, 'file' => $outputPath]
        ];

        $params = [
            'removeAlpha' => []
        ];

        Sharp::run($io, $params);

        $this->assertFileExists($outputPath);
    }
}