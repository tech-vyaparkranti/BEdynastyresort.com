<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Models\TestModel;


class TestModelController extends Controller
{
    public function storTest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image',
                'title' => 'required|string',
                'description' => 'required|string'
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $imagePath = $request->file('image')->store('images', 'public');
            $test = TestModel::create([
                'image' => $imagePath,
                'title' => $request->title,
                'description' => $request->description,
            ]);
            $data = [
                'status' => true,
                'success' => true,
                'test' => $test,
            ];

            return response()->json($data, 200);

        } catch (\Throwable $th) {
            $data = [
                'status' => false,
                'success' => false,
                'message' => $th->getMessage(),
            ];

            return response()->json($data, 422);
        }
    }

    public function testData()
    {
        $test = TestModel::all();
        $data = [
            'status' => true,
            'success' => true,
            'test' => $test,
        ];

        return response()->json($data, 200);
    }

    public function getTestDetail($id)
    {
        $test = TestModel::find($id);
        $data = [
            'status' => true,
            'success' => true,
            'test' => $test,
        ];
        return response()->json($data, 200);

    }
}
