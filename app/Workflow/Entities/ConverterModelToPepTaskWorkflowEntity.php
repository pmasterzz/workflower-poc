<?php

namespace App\Workflow\Entities;

use PHPMentors\Workflower\Persistence\PhpWorkflowSerializer;

class ConverterModelToPepTaskWorkflowEntity
{
    public function convertModelToEntity($model)
    {
        $pepTask = new PepTask();

        $pepTask->setId($model->id);
        $pepTask->setTitle($model->title);
        $pepTask->setCompleted($model->completed);

        if ($model->serialized_workflow != null) {
            $pepTask->setWorkflow($this->deserializeWorkflow($model));
            $pepTask->setSerializedWorkflow($model->serialized_workflow);
        }

        return $pepTask;
    }

    private function deserializeWorkflow($model)
    {
        $serialize = new PhpWorkflowSerializer();
        return $serialize->deserialize($model->serialized_workflow);
    }
}
