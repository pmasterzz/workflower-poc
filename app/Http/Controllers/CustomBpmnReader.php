<?php

namespace App\Http\Controllers;

use PHPMentors\Workflower\Definition\Bpmn2Reader;
use PHPMentors\Workflower\Definition\IdAttributeNotFoundException;
use PHPMentors\Workflower\Workflow\Workflow;
use PHPMentors\Workflower\Workflow\WorkflowBuilder;

class CustomBpmnReader extends Bpmn2Reader
{

    /**
     * @param array $object
     *
     * @return Workflow
     *
     * @throws IdAttributeNotFoundException
     */
    public function read($object): Workflow
    {
        return $this->buildWorkflow($object);
    }

    /**
     * @param array $object
     *
     * @return Workflow
     */
    public function readJson(array $object): Workflow
    {
        return $this->buildWorkflow($object);
    }

    private function buildWorkflow($object)
    {
        $workflowBuilder = new WorkflowBuilder(null);
        $flowObjectRoles = array();

        if (isset($object['lanes'])) {
            foreach ($object['lanes'] as $lane) {
                $workflowBuilder->addRole($lane['id'], $lane['name']);

                foreach ($lane['flowNodeRefs'] as $flowNodeRef) {
                    $flowObjectRoles[$flowNodeRef] = $lane['id'];
                }
            }
        }

        if (count($flowObjectRoles) == 0) {
            $workflowBuilder->addRole(Workflow::DEFAULT_ROLE_ID);
        }

        if (isset($object['startEvents'])) {
            foreach ($object['startEvents'] as $startEvent) {
                $workflowBuilder->addStartEvent($startEvent['id'], $this->provideRoleIdForFlowObject($flowObjectRoles, $startEvent['id']), $startEvent['name']);
            }
        }

        if (isset($object['endEvents'])) {
            foreach ($object['endEvents'] as $endEvent) {
                $workflowBuilder->addStartEvent($endEvent['id'], $this->provideRoleIdForFlowObject($flowObjectRoles, $endEvent['id']), $endEvent['name']);
            }
        }

        if (isset($object['exclusiveGateways'])) {
            foreach ($object['exclusiveGateways'] as $exclusiveGateway) {
                $workflowBuilder->addExclusiveGateway($exclusiveGateway['id'], $this->provideRoleIdForFlowObject($flowObjectRoles, $exclusiveGateway['id']), $exclusiveGateway['name']);
            }
        }

        if (isset($object['tasks'])) {
            foreach ($object['tasks'] as $task) {
                $workflowBuilder->addTask(
                    $task['id'],
                    $this->provideRoleIdForFlowObject($flowObjectRoles, $task['id']),
                    $task['name'],
                    null
                );
            }
        }

        if (isset($object['serviceTasks'])) {
            foreach ($object['serviceTasks'] as $task) {
                $workflowBuilder->addServiceTask(
                    $task['id'],
                    $this->provideRoleIdForFlowObject($flowObjectRoles, $task['id']),
                    null,
                    $task['name'],
                    null
                );
            }
        }

        if (isset($object['sequenceFlows'])) {
            foreach ($object['sequenceFlows'] as $sequenceFlow) {
                $condition = $sequenceFlow['condition'] ?? null;

                $workflowBuilder->addSequenceFlow(
                    $sequenceFlow['sourceRef'],
                    $sequenceFlow['targetRef'],
                    $sequenceFlow['id'],
                    $sequenceFlow['name'],
                    $condition
                );
            }
        }

        $build = $workflowBuilder->build();

//        return $build->serialize();
//
        return $workflowBuilder->build();
    }

    /**
     * @param array $flowObjectRoles
     * @param string $flowObjectId
     *
     * @return string
     *
     * @since Method available since Release 1.3.0
     */
    private function provideRoleIdForFlowObject(array $flowObjectRoles, $flowObjectId)
    {
        return count($flowObjectRoles) ? $flowObjectRoles[$flowObjectId] : Workflow::DEFAULT_ROLE_ID;
    }
}
