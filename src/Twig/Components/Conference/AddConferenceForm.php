<?php

namespace App\Twig\Components\Conference;

use App\Entity\Conference;
use App\Form\ConferenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class AddConferenceForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Conference $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ConferenceType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): Response {
        $this->submitForm();

        /** @var Conference $conference */
        $conference = $this->getForm()->getData();
        $entityManager->persist($conference);
        $entityManager->flush();

        return $this->redirectToRoute('app_conference_show', [
            'id' => $conference->getId(),
        ]);
    }
}
