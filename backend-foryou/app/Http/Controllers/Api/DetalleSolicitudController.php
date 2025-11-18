<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetalleSolicitud;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DetalleSolicitudController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/detalles-solicitud",
     *     tags={"Detalles Solicitud"},
     *     summary="Get list of detalle solicitudes",
     *     description="Returns list of detalle solicitudes",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/DetalleSolicitud")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(DetalleSolicitud::all());
    }

    /**
     * @OA\Post(
     *     path="/api/detalles-solicitud",
     *     tags={"Detalles Solicitud"},
     *     summary="Create a new detalle solicitud",
     *     description="Creates a new detalle solicitud",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DetalleSolicitudInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Detalle Solicitud created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DetalleSolicitud")
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
     * @OA\Get(
     *     path="/api/detalles-solicitud/{id}",
     *     tags={"Detalles Solicitud"},
     *     summary="Get detalle solicitud by ID",
     *     description="Returns a single detalle solicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of detalle solicitud to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DetalleSolicitud")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Detalle Solicitud not found"
     *     )
     * )
     */
    public function show(DetalleSolicitud $detalleSolicitud)
    {
        return response()->json($detalleSolicitud);
    }

    /**
     * @OA\Put(
     *     path="/api/detalles-solicitud/{id}",
     *     tags={"Detalles Solicitud"},
     *     summary="Update an existing detalle solicitud",
     *     description="Updates a detalle solicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of detalle solicitud to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DetalleSolicitudInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle Solicitud updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DetalleSolicitud")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Detalle Solicitud not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/detalles-solicitud/{id}",
     *     tags={"Detalles Solicitud"},
     *     summary="Delete a detalle solicitud",
     *     description="Deletes a single detalle solicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of detalle solicitud to delete",
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
     *         description="Detalle Solicitud not found"
     *     )
     * )
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
