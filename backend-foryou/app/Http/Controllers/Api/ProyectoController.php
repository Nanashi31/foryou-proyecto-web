<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Proyecto::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'observaciones' => 'nullable|string|max:255',
                'plano_url' => 'nullable|url|max:500',
                'plano_json' => 'nullable|json',
                'id_cliente' => 'nullable|uuid|exists:clientes,id_cliente',
            ]);

            $proyecto = Proyecto::create($validatedData);
            return response()->json($proyecto, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating proyecto',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Proyecto $proyecto)
    {
        return response()->json($proyecto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        try {
            $validatedData = $request->validate([
                'observaciones' => 'sometimes|nullable|string|max:255',
                'plano_url' => 'sometimes|nullable|url|max:500',
                'plano_json' => 'sometimes|nullable|json',
                'id_cliente' => 'sometimes|nullable|uuid|exists:clientes,id_cliente',
            ]);

            $proyecto->update($validatedData);
            return response()->json($proyecto);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyecto $proyecto)
    {
        try {
            $proyecto->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
