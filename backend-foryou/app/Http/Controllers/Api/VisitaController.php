<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visita;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VisitaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/visitas",
     *     tags={"Visitas"},
     *     summary="Get list of visitas",
     *     description="Returns list of visitas",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Visita")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Visita::all());
    }

    /**
     * @OA\Post(
     *     path="/api/visitas",
     *     tags={"Visitas"},
     *     summary="Create a new visita",
     *     description="Creates a new visita",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/VisitaInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Visita created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Visita")
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
     * @OA\Get(
     *     path="/api/visitas/{id}",
     *     tags={"Visitas"},
     *     summary="Get visita by ID",
     *     description="Returns a single visita",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of visita to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Visita")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Visita not found"
     *     )
     * )
     */
    public function show(Visita $visita)
    {
        return response()->json($visita);
    }

    /**
     * @OA\Put(
     *     path="/api/visitas/{id}",
     *     tags={"Visitas"},
     *     summary="Update an existing visita",
     *     description="Updates a visita",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of visita to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/VisitaInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Visita updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Visita")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Visita not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/visitas/{id}",
     *     tags={"Visitas"},
     *     summary="Delete a visita",
     *     description="Deletes a single visita",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of visita to delete",
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
     *         description="Visita not found"
     *     )
     * )
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
