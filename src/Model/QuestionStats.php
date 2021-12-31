<?php

declare(strict_types=1);

namespace BloomAtWork\Model;

/**
 * Class QuestionStats
 * @package BloomAtWork\Model
 * @description Aims to represent one of the question asked to the employees during the survey and computes all the
 * answers into stats
 * - min
 * - max
 * - mean (average)
 *
 * Requires a non empty srtring as a question to be instanciated
 */
class QuestionStats extends AbstractQuestion
{
    private AnswerStatList $answers;

    public function __construct(string $label)
    {
        if (self::isValidLabel($label)) {
            parent::__construct($label);
        } else {
            throw new \InvalidArgumentException('Invalid question label');
        }
        $this->answers = new AnswerStatList();
    }

    public static function isValidLabel(string $label): bool
    {
        //todo: implement label verification (a-zA-Z0-9_ for example ?)
        if ($label == '') {
            return false;
        }

        return true;
    }

    public function addAnswer(AnswerStat $answer): void
    {
        $this->answers->add($answer);
    }

    /**
     * Set all the answers at once the answers
     */
    public function setAnswerStats(AnswerStatList $answerStatsList) :void
    {
        $this->answers = $answerStatsList;
    }

    public function getAnswers(): AnswerStatList
    {
        return $this->answers;
    }

    /**
     * The lowest given answer
     */
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

    /**
     * The highest given answer
     */
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

    /**
     * The average of all given answers
     */
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
