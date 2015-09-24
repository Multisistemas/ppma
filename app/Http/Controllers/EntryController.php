<?php

namespace App\Http\Controllers;

use App\Model\EntryModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class EntryController extends BaseController
{
    public function create(Request $request) {
        /* @var \Illuminate\Validation\Factory $validationFactory */
        $validationFactory = app('validator');

        // create validator
        $validator = $validationFactory->make($request->all(), [
            'label'    => 'required',
            'password' => 'required'
        ]);

        // check if validation runs fine
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // save entry
        $model = new EntryModel();
        $model->label = $request->get('label');
        $model->password = $request->get('password');
        $model->save();

        // response id
        $headers = ['Locations' => sprintf('/entries/%d', $model->id)];
        return response()->json(['id' => $model->id], 201, $headers);
    }

    public function index() {
        return response()->json(EntryModel::all());
    }

    public function get($id) {
        try {
            $model = EntryModel::findOrFail($id);
            return response()->json($model);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'entry not found'], 404);
        }


        return response()->json();
    }

}