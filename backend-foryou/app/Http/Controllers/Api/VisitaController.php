<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visita;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Visita::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_empleado' => 'required|exists:empleados,id_empleado',
                'id_solicitud' => 'required|exists:solicitudes,id_solicitud',
                // 'fecha' is automatically set by the database using useCurrent()
                'observaciones' => 'nullable|string|max:255',
            ]);

            $visita = Visita::create($validatedData);
            return response()->json($visita, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating visita',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Visita $visita)
    {
        return response()->json($visita);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visita $visita)
    {
        try {
            $validatedData = $request->validate([
                'id_empleado' => 'sometimes|required|exists:empleados,id_empleado',
                'id_solicitud' => 'sometimes|required|exists:solicitudes,id_solicitud',
                'observaciones' => 'sometimes|nullable|string|max:255',
            ]);

            $visita->update($validatedData);
            return response()->json($visita);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating visita',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visita $visita)
    {
        try {
            $visita->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting visita',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
