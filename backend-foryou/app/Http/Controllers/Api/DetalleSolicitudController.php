<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetalleSolicitud;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DetalleSolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(DetalleSolicitud::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_solicitud' => 'required|exists:solicitudes,id_solicitud',
                'med_alt' => 'nullable|numeric|min:0',
                'med_anc' => 'nullable|numeric|min:0',
                'descripcion' => 'nullable|string',
            ]);

            $detalleSolicitud = DetalleSolicitud::create($validatedData);
            return response()->json($detalleSolicitud, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating detalle solicitud',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DetalleSolicitud $detalleSolicitud)
    {
        return response()->json($detalleSolicitud);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetalleSolicitud $detalleSolicitud)
    {
        try {
            $validatedData = $request->validate([
                'id_solicitud' => 'sometimes|required|exists:solicitudes,id_solicitud',
                'med_alt' => 'sometimes|nullable|numeric|min:0',
                'med_anc' => 'sometimes|nullable|numeric|min:0',
                'descripcion' => 'sometimes|nullable|string',
            ]);

            $detalleSolicitud->update($validatedData);
            return response()->json($detalleSolicitud);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating detalle solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalleSolicitud $detalleSolicitud)
    {
        try {
            $detalleSolicitud->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting detalle solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
