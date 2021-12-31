<?php

namespace BloomAtWork\Tests\Unit;

use PHPUnit\Framework\TestCase;

class QuestionStatsTest extends  TestCase
{
    public function testQuestionCanBeCreatedFromValidLabel()
    {
        $label = 'testQuestion';

        try {
            $question = new \BloomAtWork\Model\QuestionStats($label);
            $this->assertEquals($label, $question->getLabel());
        } catch (\Exception $e) {
            $this->fail('Question should be created from valid label');
        }
    }

    public function testQuestionCannotBeCreatedFromInvalidLabel()
    {
        $label = '';

        try {
            $question = new \BloomAtWork\Model\QuestionStats($label);
            $this->fail('Question should be created from valid label');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Invalid question label', $e->getMessage());
        }
    }

    public function testAnswersStatCanBeAddedToQuestionStat()
    {
        $label = 'testQuestion';
        $question = new \BloomAtWork\Model\QuestionStats($label);

        $answerStatsTest = [
            new \BloomAtWork\Model\AnswerStat(1.0),
            new \BloomAtWork\Model\AnswerStat(2.0),
            new \BloomAtWork\Model\AnswerStat(3.0),
        ];

        try {
            foreach ($answerStatsTest as $answerStat) {
                $question->addAnswer($answerStat);
            }
        } catch (\Throwable $e) {
            $this->fail('Answers couldn\'t be added to question' . $e->getMessage());
        }

        $this->assertContainsOnly('BloomAtWork\Model\AnswerStat', $question->getAnswers());
        $this->assertEquals($answerStatsTest, $question->getAnswers()->getAll());
    }

    public function testQuestionStatCanProvideMinStatValue()
    {
        $question = new \BloomAtWork\Model\QuestionStats('testQuestion 1');
        $answerStatsTest = [
            new \BloomAtWork\Model\AnswerStat(0.3125),
            new \BloomAtWork\Model\AnswerStat(2.0),
            new \BloomAtWork\Model\AnswerStat(3.0),
        ];

        try {
            foreach ($answerStatsTest as $answerStat) {
                $question->addAnswer($answerStat);
            }

            $min = $question->getMin();
            $this->assertEquals(0.3125, $min);
        } catch (\Throwable $e) {
            $this->fail('Failed to compute a minimum value. ' . $e->getMessage());
        }
    }

    public function testQuestionStatCanProvideMaxStatValue()
    {
        $question = new \BloomAtWork\Model\QuestionStats('testQuestion 1');
        $answerStatsTest = [
            new \BloomAtWork\Model\AnswerStat(0.3125),
            new \BloomAtWork\Model\AnswerStat(3.46597),
            new \BloomAtWork\Model\AnswerStat(3.46598),
        ];

        try {
            foreach ($answerStatsTest as $answerStat) {
                $question->addAnswer($answerStat);
            }

            $max = $question->getMax();
            $this->assertEquals(3.46598, $max);
        } catch (\Throwable $e) {
            $this->fail('Failed to compute a minimum value. ' . $e->getMessage());
        }
    }

    public function testQuestionStatCanProvideMeanStatValue()
    {
        $question = new \BloomAtWork\Model\QuestionStats('testQuestion 1');
        $answerStatsTest = [
            new \BloomAtWork\Model\AnswerStat(1.2),
            new \BloomAtWork\Model\AnswerStat(3.6),
            new \BloomAtWork\Model\AnswerStat(4.8),
        ];

        try {
            foreach ($answerStatsTest as $answerStat) {
                $question->addAnswer($answerStat);
            }

            $mean = $question->getMean();
            $this->assertEquals(3.2, $mean);
        } catch (\Throwable $e) {
            $this->fail('Failed to compute a minimum value. ' . $e->getMessage());
        }
    }
}
