<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Usecase\CompleteTaskUsecase;
use App\Workflow\OperationRunner\CompleteTaskListOperationRunner;
use App\Workflow\Repository\CustomWorkflowRepository;
use App\Workflow\Workflows\TaskWorkflow;
use PHPMentors\Workflower\Persistence\PhpWorkflowSerializer;
use PHPMentors\Workflower\Process\Process;

class TaskController extends Controller
{
    public function complete(Task $task)
    {
        // Find the taskList which belongs to the task
        $taskList = $task->taskList;

        $task->completed = true;
        $task->save();

        // Create a new workflow entity and set the required attributes
        $workflowEntity = new \App\Workflow\Entities\TaskList();
        $workflowEntity->setId($taskList->id);
        $workflowEntity->setTitle($taskList->name);
        $workflowEntity->setUncompletedTasks($taskList->tasks->where('completed', false)->count());

        // Create a new CompleteTaskUseCase instance and set the process.
        $useCase = new CompleteTaskUsecase();
        $useCase->setProcess($this->createTaskProcess());
        $entity = $useCase->run($workflowEntity);

        $serializer = new PhpWorkflowSerializer();

        $workflow = $entity->getWorkflow();
        $taskList->state = $workflow->getCurrentFlowObject()->getId();
        $taskList->serialized_workflow = $serializer->serialize($workflow);

        $taskList->save();

        return $taskList;
    }

    private function createTaskProcess()
    {
        $repository = new CustomWorkflowRepository();
        $taskWorkflow = new TaskWorkflow('TaskListWorflow');
        $process = new Process($taskWorkflow, $repository, new CompleteTaskListOperationRunner());

        return $process;
    }
}
