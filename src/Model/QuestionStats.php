<?php

declare(strict_types=1);

namespace BloomAtWork\Model;

class QuestionStats extends AbstractQuestion
{
    private AnswerStatList $answers;

    public function __construct(string $label)
    {
        parent::__construct($label);
        $this->answers = new AnswerStatList();
    }

    public function addAnswer(AnswerStat $answer): void
    {
        $this->answers->add($answer);
    }

    public function getAnswers(): AnswerStatList
    {
        return $this->answers;
    }

    public function getMin(): float
    {
        $min = null;

        foreach ($this->getAnswers()->getAll() as $answer) {
            if (null === $min || $answer->getValue() < $min) {
                $min = $answer->getValue();
            }
        }

        if (is_null($min)) {
            return 0;
        }

        return $min;
    }

    public function getMax(): float
    {
        $max = null;

        foreach ($this->getAnswers()->getAll() as $answer) {
            if (null === $max || $answer->getValue() > $max) {
                $max = $answer->getValue();
            }
        }

        if (is_null($max)) {
            return 0;
        }

        return $max;
    }

    public function getMean(): float
    {
        $mean = 0;
        $sum  = 0;

        foreach ($this->getAnswers()->getAll() as $answer) {
            $sum += $answer->getValue();
        }

        if ($sum > 0) {
            $mean = $sum / $this->getAnswers()->count();
        }

        return $mean;
    }
}
