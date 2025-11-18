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
     * @OA\Get(
     *     path="/api/cotizaciones",
     *     tags={"Cotizaciones"},
     *     summary="Get list of cotizaciones",
     *     description="Returns list of cotizaciones",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Cotizacion")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Cotizacion::all());
    }

    /**
     * @OA\Post(
     *     path="/api/cotizaciones",
     *     tags={"Cotizaciones"},
     *     summary="Create a new cotizacion",
     *     description="Creates a new cotizacion",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CotizacionInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cotizacion created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Cotizacion")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/cotizaciones/{id}",
     *     tags={"Cotizaciones"},
     *     summary="Get cotizacion by ID",
     *     description="Returns a single cotizacion",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of cotizacion to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Cotizacion")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cotizacion not found"
     *     )
     * )
     */
    public function show(Cotizacion $cotizacion)
    {
        return response()->json($cotizacion);
    }

    /**
     * @OA\Put(
     *     path="/api/cotizaciones/{id}",
     *     tags={"Cotizaciones"},
     *     summary="Update an existing cotizacion",
     *     description="Updates a cotizacion",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of cotizacion to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CotizacionInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cotizacion updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Cotizacion")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cotizacion not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/cotizaciones/{id}",
     *     tags={"Cotizaciones"},
     *     summary="Delete a cotizacion",
     *     description="Deletes a single cotizacion",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of cotizacion to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cotizacion not found"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/cotizaciones/suggest-materials",
     *     tags={"Cotizaciones"},
     *     summary="Suggest materials for a solicitud using AI",
     *     description="Provides AI-driven material suggestions based on a solicitud ID.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_solicitud"},
     *             @OA\Property(property="id_solicitud", type="integer", format="int64", description="ID of the solicitud for which to suggest materials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Material suggestions generated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Material suggestions generated successfully"),
     *             @OA\Property(property="solicitud_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="suggestions",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="material_name", type="string", example="PTR 1 1/2"),
     *                     @OA\Property(property="quantity", type="number", format="float", example=20),
     *                     @OA\Property(property="unit", type="string", example="m"),
     *                     @OA\Property(property="notes", type="string", example="Para marco de puerta"),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error generating material suggestions"
     *     )
     * )
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
