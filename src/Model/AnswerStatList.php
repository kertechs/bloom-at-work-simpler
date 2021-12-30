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
            $this->add($answer);
        }
    }

    public function add(AnswerStat $answer) :void
    {
        $this->answers[] = $answer;
    }

    public function getAll() :array
    {
        return $this->answers;
    }

    public function count() :int
    {
        return count($this->answers);
    }
}
