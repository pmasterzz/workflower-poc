<?php

namespace App\Workflow\Entities;

use PHPMentors\Workflower\Persistence\WorkflowSerializableInterface;
use PHPMentors\Workflower\Process\ProcessContextInterface;

class TaskList implements ProcessContextInterface, WorkflowSerializableInterface
{
    private $id;
    private $title;
    private $completed = false;
    private $workflow;
    private $serializedWorkflow;
    private $uncompletedTasks;

    public function __construct(...$args)
    {
        foreach ($args as $key => $value) {
            $this[$key] = $value;
        }
    }

    /**
     * @return mixed
     */
    public function getUncompletedTasks()
    {
        return $this->uncompletedTasks;
    }

    /**
     * @param mixed $uncompletedTasks
     */
    public function setUncompletedTasks($uncompletedTasks): void
    {
        $this->uncompletedTasks = $uncompletedTasks;
    }


    /**
     * @return PHPMentors\Workflower\Workflow\Workflow
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * @param mixed $workflow
     */
    public function setWorkflow($workflow): void
    {
        $this->workflow = $workflow;
    }

    /**
     * @return mixed
     */
    public function getSerializedWorkflow()
    {
        if (is_resource($this->serializedWorkflow)) {
            return stream_get_contents($this->serializedWorkflow, -1, 0);
        }

        return $this->serializedWorkflow;
    }

    /**
     * @param mixed $serializedWorkflow
     */
    public function setSerializedWorkflow($serializedWorkflow): void
    {
        $this->serializedWorkflow = $serializedWorkflow;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param mixed $completed
     */
    public function setCompleted($completed): void
    {
        $this->completed = $completed;
    }

    public function getProcessData()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'completed' => $this->completed,
            'uncompletedTasks' => $this->uncompletedTasks,
            'data' => $this,
        ];
    }

    public function __toString()
    {
        return print_r($this->getProcessData(), true);
    }
}
