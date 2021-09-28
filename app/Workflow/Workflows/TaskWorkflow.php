<?php

namespace App\Workflow\Workflows;

class TaskWorkflow implements \PHPMentors\Workflower\Process\WorkflowContextInterface
{
    private $workflowId;

    public function __construct(string $id)
    {
        $this->workflowId = $id;
    }

    public function getWorkflowId()
    {
        return $this->workflowId;
    }

}
