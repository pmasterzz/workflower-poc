<?php

namespace App\Usecase;

use App\Participants\DefaultParticipant;
use App\Workflow\Entities\TaskList;
use PHPMentors\Workflower\Process\EventContext;
use PHPMentors\Workflower\Process\Process;
use PHPMentors\Workflower\Process\ProcessAwareInterface;
use PHPMentors\Workflower\Process\WorkItemContext;

class CompleteTaskUsecase implements ProcessAwareInterface
{
    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    public function run(TaskList $taskList)
    {
        // Set the default role for the process
        $participant = new DefaultParticipant();
        // Define what task to start with and define the context for the workflow
        $eventContext = new EventContext('start1', $taskList);
        // Start the process based on the eventContext
        $this->process->start($eventContext);

        // Create a new work context
        $workItem = new WorkItemContext($participant);
        $workItem->setProcessContext($taskList);
        $workItem->setActivityId($taskList->getWorkflow()->getCurrentFlowObject()->getId());

        // Start the process
        $this->process->allocateWorkItem($workItem);
        $this->process->startWorkItem($workItem);
        $this->process->completeWorkItem($workItem);

        return $taskList;
    }
}
