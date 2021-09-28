<?php

namespace App\Workflow\OperationRunner;

use App\Models\TaskList;
use App\Participants\DefaultParticipant;
use PHPMentors\Workflower\Workflow\Operation\OperationalInterface;
use PHPMentors\Workflower\Workflow\Operation\OperationRunnerInterface;
use PHPMentors\Workflower\Workflow\Workflow;

class CompleteTaskListOperationRunner implements OperationRunnerInterface
{
    public function provideParticipant(OperationalInterface $operational, Workflow $workflow)
    {
        return new DefaultParticipant();
    }

    public function run(OperationalInterface $operational, Workflow $workflow)
    {
        $processData = $workflow->getProcessData();
        $taskList = TaskList::findOrFail($processData['id']);
        $taskList->completed = true;

        $taskList->save();
    }
}
