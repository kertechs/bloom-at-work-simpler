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
}
