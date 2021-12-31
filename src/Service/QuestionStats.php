<?php

declare(strict_types=1);

namespace BloomAtWork\Service;

use BloomAtWork\Model\AnswerStat;
use BloomAtWork\Model\AnswerStatList;

/**
 * Class QuestionStats
 * @package BloomAtWork\Service
 * @description Service offering to load from a csv file after its validation :
 * - a Model\QuestionStat object
 * - a list of associated Mode\AnswerStat
 * - expose a method to retrieve the generated QuestionStat object
 */
class QuestionStats
{
    //Defines the max number of columns expected/allowed in the parsed csv file
    const MAX_CSV_COLUMNS = 1;

    //Defines the first answer line positions in the file
    const ANSWER_STAT_START_LINE = 1;

    private \SplFileObject $csvFile;
    private ?\BloomAtWork\Model\QuestionStats $questionStats = null;

    /**
     * @throws \Exception
     */
    public function __construct(string $csvFilePath)
    {
        try {
            $this->csvFile = new \SplFileObject($csvFilePath);
            $this->validateFile();
        } catch (\RuntimeException $e) {
            throw new \InvalidArgumentException('Unable to open file: ' . $csvFilePath);
        }
    }

    //Validation rules for the csv file
    private function validateFile() :void
    {
        if (!$this->csvFile->isReadable()) {
            throw new \RuntimeException('File is not readable');
        }

        if (!$this->csvFile->isFile()) {
            throw new \RuntimeException('File is not a file');
        }

        if (!$this->csvFile->getSize()) {
            throw new \RuntimeException('File is empty');
        }
    }

    //Label for the question should be the first line of the csv file
    private function getQuestionStatsLabel() :string
    {
        $this->csvFile->setFlags(\SplFileObject::READ_CSV);
        $this->csvFile->setCsvControl(';');
        $this->csvFile->rewind();
        $line = $this->csvFile->fgetcsv();

        if (!is_array($line)) {
            $line = [];
        }

        if (count($line) == self::MAX_CSV_COLUMNS && \BloomAtWork\Model\QuestionStats::isValidLabel($line[0])) {
            return $line[0];
        }

        return '';
    }

    /**
     * Builds an answers list from all the valid values found in the csf file
     */
    private function getAnswerStatList(): AnswerStatList
    {
        $this->csvFile->setFlags(\SplFileObject::READ_CSV);
        $this->csvFile->setCsvControl(';');
        $this->csvFile->rewind();
        $this->csvFile->fgetcsv(); //skip the first line
        //$this->csvFile->seek(self::ANSWER_STAT_START_LINE);

        $answerStatList = new AnswerStatList();
        while (!$this->csvFile->eof()) {
            $line = $this->csvFile->fgetcsv();

            if (!is_array($line)) {
                $line = [];
            }

            try {
                //check if the line is valid :
                //  the number of columns should be 1
                //  and the value should be a string representing a float
                if ( count($line) == self::MAX_CSV_COLUMNS && is_numeric($line[0]) && (float)$line[0] == $line[0] ) {
                    $answerStat = new AnswerStat( (float) $line[0] );
                    $answerStatList->add($answerStat);
                }
                else {
                    //todo: log the invalid line and/or throw exception
                    throw new \Exception('Invalid answer line');
                }
            } catch (\Throwable $e) {
                //Ignore invalid lines
                continue;
            }
        }

        return $answerStatList;
    }

    private function getQuestionStatsQuestion() :\BloomAtWork\Model\QuestionStats
    {
        return new \BloomAtWork\Model\QuestionStats($this->getQuestionStatsLabel());
    }

    /**
     * @return \BloomAtWork\Model\QuestionStats
     */
    public function getQuestionStats() :\BloomAtWork\Model\QuestionStats
    {
        $this->validateFile();
        if (is_null($this->questionStats)) {
            $questionStats = $this->getQuestionStatsQuestion();
            $answerStatsList = $this->getAnswerStatList();

            $questionStats->setAnswerStats($answerStatsList);
            $this->questionStats = $questionStats;
        }

        return $this->questionStats;
    }

    public function __invoke() :\BloomAtWork\Model\QuestionStats
    {
        return $this->getQuestionStats();
    }
}