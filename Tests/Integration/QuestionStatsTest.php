<?php

declare(strict_types=1);

namespace BloomAtWork\Tests\Integration;

use BloomAtWork\Model\AnswerStat;
use BloomAtWork\Model\AnswerStatList;

class QuestionStatsTest extends \PHPUnit\Framework\TestCase
{
    private string $staticValidCsvFile;
    private string $staticInvalidQuestionCsvFile;
    private string $staticInvalidAnswersCsvFile;
    private string $staticMissingCsvFile;
    private string $staticEmptyCsvFile;

    public function setUp(): void
    {
        parent::setUp();
        $this->staticValidCsvFile = __DIR__ . '/../csv/my-test-file.csv';
        $this->staticInvalidQuestionCsvFile = __DIR__ . '/../csv/my-bad-test-file.csv';
        $this->staticInvalidAnswersCsvFile = __DIR__ . '/../csv/my-test-file-with-bad-answers.csv';
        $this->staticMissingCsvFile = 'Scooby Doo where are you ?';
        $this->staticEmptyCsvFile = __DIR__ . '/../csv/my-empty-test-file.csv';
    }

    public function testQuestionStatServiceCantBeInstantiatedWithInvalidFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to open file');
        $service = new \BloomAtWork\Service\QuestionStats($this->staticMissingCsvFile);
    }

    public function testQuestionStatServiceCantBeInstantiatedWithEmptyFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to open file');
        $service = new \BloomAtWork\Service\QuestionStats($this->staticEmptyCsvFile);
    }

    public function testQuestionStatCanBeCreatedFromValidCsvFile()
    {
        $expectedLabel = '# Coucou Hibou';
        $expectedAnswerStatList = new AnswerStatList([
            new AnswerStat(1.0),
            new AnswerStat(2.0),
        ]);
        $service = new \BloomAtWork\Service\QuestionStats($this->staticValidCsvFile);
        $questionStats = $service->getQuestionStats();

        $this->assertInstanceOf(\BloomAtWork\Model\QuestionStats::class, $questionStats);
        $this->assertEquals($expectedLabel, $questionStats->getLabel());
        $this->assertContainsOnly(AnswerStat::class, $questionStats->getAnswers());
        $this->assertEquals($expectedAnswerStatList->getAll(), $questionStats->getAnswers()->getAll());
    }

    public function testQuestionStatCantBeCreatedFromCsvFileWithInvalidQuestion()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid question label');
        $service = new \BloomAtWork\Service\QuestionStats($this->staticInvalidQuestionCsvFile);
        $service->getQuestionStats();
    }

    public function testQuestionStatCanBeCreatedFromCsvFileWithInvalidAnswers()
    {
        $expectedLabel = '# Coucou Hibou';
        $expectedAnswerStatList = new AnswerStatList([
            new AnswerStat(1.0),
            new AnswerStat(2.0),
        ]);

        $service = new \BloomAtWork\Service\QuestionStats($this->staticInvalidAnswersCsvFile);
        $questionStats = $service->getQuestionStats();

        $this->assertInstanceOf(\BloomAtWork\Model\QuestionStats::class, $questionStats);
        $this->assertEquals($expectedLabel, $questionStats->getLabel());
        $this->assertContainsOnly(AnswerStat::class, $questionStats->getAnswers());
        $this->assertEquals($expectedAnswerStatList->getAll(), $questionStats->getAnswers()->getAll());
    }

    public function testQuestionStatCanBeRetrievedRightFromInvoke()
    {
        $expectedLabel = '# Coucou Hibou';
        $expectedAnswerStatList = new AnswerStatList([
            new AnswerStat(1.0),
            new AnswerStat(2.0),
        ]);

        $questionStats = (new \BloomAtWork\Service\QuestionStats( $this->staticValidCsvFile) )();

        $this->assertInstanceOf(\BloomAtWork\Model\QuestionStats::class, $questionStats);
        $this->assertEquals($expectedLabel, $questionStats->getLabel());
        $this->assertContainsOnly(AnswerStat::class, $questionStats->getAnswers());
        $this->assertEquals($expectedAnswerStatList->getAll(), $questionStats->getAnswers()->getAll());
    }
}
