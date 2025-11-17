<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Cotizacion::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_solicitud' => 'required|exists:solicitudes,id_solicitud',
                'costo_total' => 'required|numeric|min:0',
                'notas' => 'nullable|string|max:255',
            ]);

            $cotizacion = Cotizacion::create($validatedData);
            return response()->json($cotizacion, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating cotizacion',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cotizacion $cotizacion)
    {
        return response()->json($cotizacion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        try {
            $validatedData = $request->validate([
                'id_solicitud' => 'sometimes|required|exists:solicitudes,id_solicitud',
                'costo_total' => 'sometimes|required|numeric|min:0',
                'notas' => 'sometimes|nullable|string|max:255',
            ]);

            $cotizacion->update($validatedData);
            return response()->json($cotizacion);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating cotizacion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cotizacion $cotizacion)
    {
        try {
            $cotizacion->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting cotizacion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suggest materials for a solicitud using AI.
     */
    public function suggestMaterials(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_solicitud' => 'required|exists:solicitudes,id_solicitud',
            ]);

            $solicitud = Solicitud::find($validatedData['id_solicitud']);

            // Simulate AI response based on solicitud description and type
            $suggestedMaterials = $this->simulateAiMaterialSuggestion($solicitud);

            return response()->json([
                'message' => 'Material suggestions generated successfully',
                'solicitud_id' => $solicitud->id_solicitud,
                'suggestions' => $suggestedMaterials
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error generating material suggestions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simulate AI material suggestion based on solicitud details.
     * In a real application, this would call an external AI API.
     */
    private function simulateAiMaterialSuggestion(Solicitud $solicitud)
    {
        $suggestions = [];

        // Basic logic based on solicitud type or description for demonstration
        if (stripos($solicitud->tipo_proyecto, 'puerta') !== false || stripos($solicitud->descripcion, 'puerta') !== false) {
            $suggestions[] = ['material_name' => 'PTR 1 1/2"', 'quantity' => 20, 'unit' => 'm', 'notes' => 'Para marco de puerta'];
            $suggestions[] = ['material_name' => 'Lamina calibre 18', 'quantity' => 4, 'unit' => 'm2', 'notes' => 'Para forro de puerta'];
            $suggestions[] = ['material_name' => 'Bisagra de 4"', 'quantity' => 3, 'unit' => 'pz', 'notes' => 'Para pivoteo'];
        } elseif (stripos($solicitud->tipo_proyecto, 'ventana') !== false || stripos($solicitud->descripcion, 'ventana') !== false) {
            $suggestions[] = ['material_name' => 'PTR 1"', 'quantity' => 10, 'unit' => 'm', 'notes' => 'Para marco de ventana'];
            $suggestions[] = ['material_name' => 'Cristal 6mm', 'quantity' => 2, 'unit' => 'm2', 'notes' => 'Para ventana'];
        } else {
            $suggestions[] = ['material_name' => 'Solera 1/8x1"', 'quantity' => 15, 'unit' => 'm', 'notes' => 'Material genérico'];
            $suggestions[] = ['material_name' => 'Electrodos 6013', 'quantity' => 1, 'unit' => 'kg', 'notes' => 'Consumible'];
        }

        // Add details from detalles_solicitud if available
        foreach ($solicitud->detallesSolicitud as $detalle) {
            $suggestions[] = [
                'material_name' => 'Material sugerido para detalle',
                'quantity' => ($detalle->med_alt && $detalle->med_anc) ? ($detalle->med_alt * $detalle->med_anc * 0.5) : 1,
                'unit' => 'unidad',
                'notes' => 'Basado en: ' . $detalle->descripcion . ' (Medidas: ' . $detalle->med_alt . 'x' . $detalle->med_anc . ')',
            ];
        }

        return $suggestions;
    }
}
