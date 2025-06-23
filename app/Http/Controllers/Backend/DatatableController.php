<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DatatableController extends Controller
{
     public function updateSortOrder(DatatableRequest $request): JsonResponse
    {
        try {
            $modelClass = '\\App\\Models\\' . $request->model;
            if (!class_exists($modelClass) || !is_subclass_of($modelClass, Model::class)) {
                return response()->json(['success' => false, 'message' => 'Invalid model'], 400);
            }

            // Update sort_order for each model based on new order
            foreach ($request->datas as $d) {
                $dbData = $modelClass::find($d['id']);
                $dbData->sort_order = $d['newOrder']; // Set sort_order starting from 1
                $dbData->save();
            }
            return response()->json(['success' => true, 'message' => 'Sort order updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => $e->getMessage()], 400);
        }
    }
}
