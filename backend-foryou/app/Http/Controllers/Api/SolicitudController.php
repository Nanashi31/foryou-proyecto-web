<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Solicitud::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'direccion' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                // 'fecha' is automatically set by the database using useCurrent()
                'id_cliente' => 'nullable|uuid|exists:clientes,id_cliente',
                'dias_disponibles' => 'nullable|string',
                'fecha_cita' => 'nullable|date',
                'materiales' => 'nullable|string',
                'tipo_proyecto' => 'nullable|string',
            ]);

            $solicitud = Solicitud::create($validatedData);
            return response()->json($solicitud, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating solicitud',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Solicitud $solicitud)
    {
        return response()->json($solicitud);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Solicitud $solicitud)
    {
        try {
            $validatedData = $request->validate([
                'direccion' => 'sometimes|required|string|max:255',
                'descripcion' => 'sometimes|nullable|string',
                'id_cliente' => 'sometimes|nullable|uuid|exists:clientes,id_cliente',
                'dias_disponibles' => 'sometimes|nullable|string',
                'fecha_cita' => 'sometimes|nullable|date',
                'materiales' => 'sometimes|nullable|string',
                'tipo_proyecto' => 'sometimes|nullable|string',
            ]);

            $solicitud->update($validatedData);
            return response()->json($solicitud);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitud $solicitud)
    {
        try {
            $solicitud->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
