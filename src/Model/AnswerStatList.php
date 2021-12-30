<?php

declare(strict_types=1);

namespace BloomAtWork\Model;

class AnswerStatList extends \ArrayIterator
{
    /**
     * @var AnswerStat[]
     */
    private array $answers = [];

    /**
     * @param AnswerStat[] $answers
     */
    public function __construct(array $answers = [])
    {
        parent::__construct();
        foreach($answers as $answer) {
            $this->addAnswer($answer);
        }
    }

    private function addAnswer(AnswerStat $answer) :void
    {
        $this->answers[] = $answer;
    }

    public function getAll() :array
    {
        return $this->answers;
    }
}
