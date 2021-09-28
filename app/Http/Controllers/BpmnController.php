<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;

class BpmnController extends Controller
{
    public $bpmnReader;

    public function __construct()
    {
        $this->bpmnReader = new CustomBpmnReader();
    }

    public function get(Request $request)
    {
        $obj = $this->bpmnReader->read($request->all());

        $workflow = new Workflow([
            'serialized_workflow' => $obj->serialize(),
            'name' => $request->input('name'),
        ]);
        $workflow->save();

        return 'success';
    }
}
