<?php


namespace App\Service\Club;


use App\Entity\Club;
use App\Entity\League;

class ClubData
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var League
     */
    private $league;

    /**
     * @var string
     */
    private $form;

    /**
     * @var int
     */
    private $apiId;

    /**
     * @var int
     */
    private $formRound;

    /**
     * @var int
     */
    private $sportsmonkApiId;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->league;
    }

    /**
     * @param League $league
     */
    public function setLeague(League $league): void
    {
        $this->league = $league;
    }

    /**
     * @return string
     */
    public function getForm(): string
    {
        return $this->form;
    }

    /**
     * @param string $form
     */
    public function setForm(string $form): void
    {
        $this->form = $form;
    }

    /**
     * @return int
     */
    public function getApiId(): int
    {
        return $this->apiId;
    }

    /**
     * @param int $apiId
     */
    public function setApiId(int $apiId): void
    {
        $this->apiId = $apiId;
    }

    /**
     * @return int
     */
    public function getFormRound(): int
    {
        return $this->formRound;
    }

    /**
     * @param int $formRound
     */
    public function setFormRound(int $formRound): void
    {
        $this->formRound = $formRound;
    }

    /**
     * @return int
     */
    public function getSportsmonkApiId(): int
    {
        return $this->sportsmonkApiId;
    }

    /**
     * @param int $sportsmonkApiId
     */
    public function setSportsmonkApiId(int $sportsmonkApiId): void
    {
        $this->sportsmonkApiId = $sportsmonkApiId;
    }

    /**
     * @param Club $club
     * @return ClubData
     */
    public function initFrom(Club $club): ClubData
    {
        $clubData = new self();
        $clubData->setApiId($club->getApiId());
        $clubData->setSportsmonkApiId($club->getSportmonksApiId());
        $clubData->setForm($club->getCurrentForm());
        $clubData->setName($club->getName());
        $clubData->setLeague($club->getLeague());
        $clubData->setFormRound($club->getFormRound());

        return $clubData;
    }
}
