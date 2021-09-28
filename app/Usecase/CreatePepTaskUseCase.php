<?php

namespace App\Usecase;

use App\Participants\DefaultParticipant;
use App\Participants\Dev;
use App\Workflow\Entities\PepTask;
use PHPMentors\Workflower\Process\EventContext;
use PHPMentors\Workflower\Process\Process;
use PHPMentors\Workflower\Process\ProcessAwareInterface;
use PHPMentors\Workflower\Process\WorkItemContext;
use PHPMentors\Workflower\Workflow\Workflow;

class CreatePepTaskUseCase implements ProcessAwareInterface
{
    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    public function run(PepTask $pepTask)
    {
        $participant = new DefaultParticipant();

        $eventContext = new EventContext('start1', $pepTask);

        $this->process->start($eventContext);

        $workItem = new WorkItemContext($participant);
        $workItem->setProcessContext($pepTask);
        $workItem->setActivityId($pepTask->getWorkflow()->getCurrentFlowObject()->getId());
        $this->process->allocateWorkItem($workItem);
        $this->process->startWorkItem($workItem);
        $this->process->completeWorkItem($workItem);

        return $pepTask;
    }
}
