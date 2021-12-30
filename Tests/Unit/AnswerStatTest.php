<?php

declare(strict_types=1);

namespace BloomAtWork\Tests\Unit;

use PHPUnit\Framework\TestCase;

class AnswerStatTest extends TestCase
{
    public function testAnswerStatCanBeCreateFromValidValue()
    {
        $value = 1.0;

        try {
            $answerStat = new \BloomAtWork\Model\AnswerStat($value);
            $this->assertEquals($value, $answerStat->getValue());
        } catch (\Throwable $e) {
            dd($e->getMessage());
            $this->fail('An unexpected exception was thrown');
        }
    }

    public function testAnswerStatCantBeCreatedIfValueIsLowerThanZero()
    {
        $value = -1.0;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Value must be greater than 0 and lower than 10');

        $answerStat = new \BloomAtWork\Model\AnswerStat($value);
    }

    public function testAnswerStatCantBeCreatedIfValueIsGreaterThanTen()
    {
        $value = 11.0;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Value must be greater than 0 and lower than 10');

        $answerStat = new \BloomAtWork\Model\AnswerStat($value);
    }

    public function testAnswerStatCantBeCreatedIfValueIsNotAFloat()
    {
        $testValues = [
            'string' => 'string',
            'bool' => true,
            'array' => ['yop'],
            'object' => new \stdClass(),
        ];

        foreach ($testValues as $lbl => $value) {
            try {
                $answerStat = new \BloomAtWork\Model\AnswerStat($value);
                $this->fail('An exception was expected');
            } catch (\Throwable $e) {
                $this->assertStringContainsString('must be of type float', $e->getMessage());
            }
        }
    }

    public function testAnswerStatListCanBeCreatedFromAnArrayOfAnswerStat()
    {
        $answerStatsTest = [
            new \BloomAtWork\Model\AnswerStat(1.0),
            new \BloomAtWork\Model\AnswerStat(2.0),
            new \BloomAtWork\Model\AnswerStat(3.0),
        ];

        try {
            $answerStatList = new \BloomAtWork\Model\AnswerStatList($answerStatsTest);
            $this->assertEquals($answerStatsTest, $answerStatList->getAll());
        } catch (\Throwable $e) {
            $this->fail('An unexpected exception was thrown');
        }
    }
}