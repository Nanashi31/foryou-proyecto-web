<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SolicitudController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/solicitudes",
     *     tags={"Solicitudes"},
     *     summary="Get list of solicitudes",
     *     description="Returns list of solicitudes",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Solicitud")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Solicitud::all());
    }

    /**
     * @OA\Post(
     *     path="/api/solicitudes",
     *     tags={"Solicitudes"},
     *     summary="Create a new solicitud",
     *     description="Creates a new solicitud",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SolicitudInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Solicitud created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Solicitud")
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
     * @OA\Get(
     *     path="/api/solicitudes/{id}",
     *     tags={"Solicitudes"},
     *     summary="Get solicitud by ID",
     *     description="Returns a single solicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of solicitud to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Solicitud")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud not found"
     *     )
     * )
     */
    public function show(Solicitud $solicitud)
    {
        return response()->json($solicitud);
    }

    /**
     * @OA\Put(
     *     path="/api/solicitudes/{id}",
     *     tags={"Solicitudes"},
     *     summary="Update an existing solicitud",
     *     description="Updates a solicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of solicitud to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SolicitudInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitud updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Solicitud")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/solicitudes/{id}",
     *     tags={"Solicitudes"},
     *     summary="Delete a solicitud",
     *     description="Deletes a single solicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of solicitud to delete",
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
     *         description="Solicitud not found"
     *     )
     * )
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
