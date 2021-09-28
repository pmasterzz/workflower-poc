<?php

namespace App\Usecase;

use App\Models\Workflow;
use App\Participants\Dev;
use App\Workflow\Entities\PullRequest;
use PHPMentors\Workflower\Process\EventContext;
use PHPMentors\Workflower\Process\Process;
use PHPMentors\Workflower\Process\WorkItemContext;
use PHPMentors\Workflower\Process\ProcessAwareInterface;

class CreatePullRequestUsecase implements ProcessAwareInterface
{

    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    public function run(PullRequest $pullRequest)
    {
        $dev = new Dev();
        // get workflow
        $workflow = Workflow::where('id', 1)->get();

        $eventContext = new EventContext('StartEvent_1', $pullRequest);

        $this->process->start($eventContext);

        $workItem = new WorkItemContext($dev);
        $workItem->setProcessContext($pullRequest);
        $workItem->setActivityId($pullRequest->getWorkflow()->getCurrentFlowObject()->getId());
        $this->process->allocateWorkItem($workItem);
        $this->process->startWorkItem($workItem);
        $this->process->completeWorkItem($workItem);

        return $pullRequest;
    }
}
