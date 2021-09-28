<?php

namespace App\Participants;

use PHPMentors\Workflower\Workflow\Participant\ParticipantInterface;
use PHPMentors\Workflower\Workflow\Resource\ResourceInterface;
use PHPMentors\Workflower\Workflow\Workflow;

class DefaultParticipant implements ParticipantInterface
{

    public function getId()
    {
        return Workflow::DEFAULT_ROLE_ID;
    }

    public function hasRole($role)
    {
         return $role === Workflow::DEFAULT_ROLE_ID;
    }

    public function setResource(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getName()
    {
        return Workflow::DEFAULT_ROLE_ID;
    }
}
