<?php


namespace App\Service\LiveFormTable;


class CandidateResult
{
    /**
     * @var array
     */
    private $matches;

    /**
     * @var array
     */
    private $errors;

    /**
     * CandidateResult constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * @param array $matches
     */
    public function setMatches(array $matches): void
    {
        $this->matches = $matches;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}