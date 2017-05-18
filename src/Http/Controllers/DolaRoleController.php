<?php

namespace DFZ\Dola\Http\Controllers;

use Illuminate\Http\Request;
use DFZ\Dola\Facades\Dola;

class DolaRoleController extends DolaBreadController
{
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        Dola::canOrFail('edit_roles');

        $slug = $this->getSlug($request);

        $dataType = Dola::model('DataType')->where('slug', '=', $slug)->first();

        //Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
            ->route("dola.{$dataType->slug}.index")
            ->with([
                'message'    => "Successfully Updated {$dataType->display_name_singular}",
                'alert-type' => 'success',
                ]);
        }
    }

    // POST BRE(A)D
    public function store(Request $request)
    {
        Dola::canOrFail('add_roles');

        $slug = $this->getSlug($request);

        $dataType = Dola::model('DataType')->where('slug', '=', $slug)->first();

        //Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $data = new $dataType->model_name();
            $this->insertUpdateData($request, $slug, $dataType->addRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
            ->route("dola.{$dataType->slug}.index")
            ->with([
                'message'    => "Successfully Added New {$dataType->display_name_singular}",
                'alert-type' => 'success',
                ]);
        }
    }
}
