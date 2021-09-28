<?php

namespace App\Http\Controllers;

use App\Models\PullRequest;
use App\PepTask;
use App\Usecase\CreatePepTaskUseCase;
use App\Usecase\CreatePullRequestUsecase;
use App\Usecase\FixPullRequestUsecase;
use App\Usecase\ReviewPullRequestUsecase;
use App\Workflow\Entities\ConverterModelToPepTaskWorkflowEntity;
use App\Workflow\Entities\ConverterModelToWorkflowEntity;
use App\Workflow\Entities\PullRequest as PullRequestEntity;
use App\Workflow\OperationRunner\MergePullRequestOperationRunner;
use App\Workflow\Repository\CustomWorkflowRepository;
use App\Workflow\Repository\WorkflowRepository;
use App\Workflow\Workflows\PullRequestWorkflow;
use App\Workflow\Workflows\TaskWorkflow;
use Illuminate\Http\Request;
use PHPMentors\Workflower\Persistence\PhpWorkflowSerializer;
use PHPMentors\Workflower\Process\Process;

class PullRequestController extends Controller
{

    public function index()
    {
        $pullRequests = PullRequest::all();
        return view('index', compact('pullRequests'));
    }

    public function createTask(Request $request)
    {
        // Create task
        $task = new PepTask(['title' => $request->input('title')]);
//        $task->save();

        $converter = new ConverterModelToPepTaskWorkflowEntity();
        $entity = $converter->convertModelToEntity($task);

        $usecase = new CreatePepTaskUseCase();
        $usecase->setProcess($this->createTaskProcess());
        $entity = $usecase->run($entity);

        $serializer = new PhpWorkflowSerializer();

        $task->title = $entity->getTitle();
        $task->completed = $entity->getCompleted();
        $workflow = $entity->getWorkflow();
        $task->state = $workflow->getCurrentFlowObject()->getId();
        $task->workflow = $serializer->serialize($workflow);

        $task->save();

        // save result to DB...

        // Search for workflow -> copy to task table?
    }

    public function getCreatePullRequest(PullRequest $pullRequest)
    {
        return view("create", compact('pullRequest'));
    }

    public function postCreatePullRequest(Request $request)
    {
        $model = $this->makeModelPullRequest($request);
        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executeCreateUseCase($entity);

        $this->savePullRequest($model, $entityUpdated);

        return 'x';
//        return redirect()->route('index');
    }

    public function getReviewPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("review", compact('pull_request'));
    }

    public function postReviewPullRequest(Request $request)
    {
        $model = PullRequest::findOrFail($request->id);
        $model->approved = $request->approved;
        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executeReviewUseCase($entity);

        $this->savePullRequest($model, $entityUpdated);

        return redirect()->route('index');
    }

    public function getFixPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("fix", compact('pull_request'));
    }

    public function postFixPullRequest(Request $request)
    {
        $model = PullRequest::findOrFail($request->id);
        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executeFixUseCase($entity);

        $this->savePullRequest($model, $entityUpdated);

        return redirect()->route('index');
    }

    private function makeModelPullRequest($request)
    {
        $pullRequest = new PullRequest();
        $pullRequest->title = $request->title;
        $pullRequest->merged = false;
        $pullRequest->approved = false;
        return $pullRequest;
    }

    private function executeCreateUseCase($entity)
    {
        $usecase = new CreatePullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);
        return $entity;
    }

    private function executeReviewUseCase($entity)
    {
        $usecase = new ReviewPullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);

        return $entity;
    }

    private function executeFixUseCase($entity)
    {
        $usecase = new FixPullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);

        return $entity;
    }

    private function convertModelToEntity($model)
    {
        $converter = new ConverterModelToWorkflowEntity();
        return $converter->convertModelToEntity($model);
    }

    private function createTaskProcess()
    {
        $repository = new CustomWorkflowRepository();
        $taskWorkflow = new TaskWorkflow(1);
        $process = new Process($taskWorkflow, $repository, new MergePullRequestOperationRunner());

        return $process;
    }

    private function createProcess()
    {
        $repository = new WorkflowRepository();
        $pullRequestWorkflow = new PullRequestWorkflow();
        $operationRunner = new MergePullRequestOperationRunner();
        $process = new Process($pullRequestWorkflow, $repository, $operationRunner);

        return $process;
    }

    private function savePullRequest(PullRequest $pullRequest, PullRequestEntity $pullRequestWorkflow)
    {
        $serializer = new PhpWorkflowSerializer();

        $pullRequest->title = $pullRequestWorkflow->getTitle();
        $pullRequest->approved = $pullRequestWorkflow->isApproved();
        $workflow = $pullRequestWorkflow->getWorkflow();
        $pullRequest->state = $workflow->getCurrentFlowObject()->getId();
        $pullRequest->serialized_workflow = $serializer->serialize($workflow);

        $pullRequest->save();
    }
}
