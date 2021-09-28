<?php

namespace App\Workflow\Repository;

use App\Models\Workflow;
use App\Workflow\Exceptions\UnsupportedMethodException;
use PHPMentors\Workflower\Workflow\WorkflowRepositoryInterface;
use PHPMentors\Workflower\Workflow\Workflow as Workflower;

class CustomWorkflowRepository implements WorkflowRepositoryInterface
{
    public function add(\PHPMentors\DomainKata\Entity\EntityInterface $entity)
    {
        throw new UnsupportedMethodException("Unsupported method", 1);
    }

    public function remove(\PHPMentors\DomainKata\Entity\EntityInterface $entity)
    {
        throw new UnsupportedMethodException("Unsupported method", 1);

    }

    /**
     * @param int|string $id
     * @return \PHPMentors\Workflower\Workflow\Workflow|void
     */
    public function findById($id)
    {
        $workflow = Workflow::where('name', $id)->first();
        if ($workflow) {
            $workflowObj = new Workflower($workflow->id, $workflow->name);
            $workflowObj->unserialize($workflow->serialized_workflow);
        }

        return $workflowObj;

    }
}
