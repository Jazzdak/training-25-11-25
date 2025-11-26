<?php

namespace App\Twig\Components\Conference;

use App\Entity\Conference;
use App\Entity\Vote;
use App\Form\AddVoteType;
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
final class AddVote extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public Conference $conference;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AddVoteType::class);
    }

    #[LiveAction]
    public function addVote(EntityManagerInterface $manager): Response
    {
        $this->submitForm();
        /** @var Vote $vote */
        $vote = $this->getForm()->getData();
        $vote->setPoll($this->conference->getPoll());

        $manager->persist($vote);
        $manager->flush();

        return $this->redirectToRoute('app_conference_show', ['id' => $this->conference->getId()]);
    }
}
