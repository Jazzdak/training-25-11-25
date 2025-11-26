<?php

namespace App\Twig\Components\Conference;

use App\Repository\ConferenceRepository;
use DateTimeImmutable;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ConferenceList
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?DateTimeImmutable $fromDate = null;
    #[LiveProp]
    public ?DateTimeImmutable $toDate = null;

    public function __construct(private ConferenceRepository $conferenceRepository)
    {
    }

    public function getConferences(): array
    {
        if (null === $this->fromDate && null === $this->toDate) {
            return $this->conferenceRepository->findAll();
        }

        return $this->conferenceRepository->findConferencesBetweenDates($this->fromDate, $this->toDate);
    }

}
