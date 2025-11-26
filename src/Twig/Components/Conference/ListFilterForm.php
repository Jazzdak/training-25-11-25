<?php

namespace App\Twig\Components\Conference;

use App\Form\ConferencesFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ListFilterForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(writable: true, url: true)]
    public ?\DateTimeImmutable $fromDate = null;
    #[LiveProp(writable: true, url: true)]
    public ?\DateTimeImmutable $toDate = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ConferencesFilterType::class, [
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ], [
            'csrf_protection' => false,
        ]);
    }

    #[LiveAction]
    public function filter(): Response {
        $this->submitForm();

        $datas = $this->getForm()->getData();

        $this->fromDate = $datas['fromDate'];
        $this->toDate = $datas['toDate'];

        return $this->redirectToRoute('app_conference_list', [
            'from_date' => $this->fromDate?->format('Y-m-d'),
            'to_date' => $this->toDate?->format('Y-m-d'),
        ]);
    }
}
