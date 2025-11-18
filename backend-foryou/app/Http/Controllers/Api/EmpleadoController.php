<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EmpleadoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/empleados",
     *     tags={"Empleados"},
     *     summary="Get list of empleados",
     *     description="Returns list of empleados",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Empleado")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Empleado::all());
    }

    /**
     * @OA\Post(
     *     path="/api/empleados",
     *     tags={"Empleados"},
     *     summary="Create a new empleado",
     *     description="Creates a new empleado",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EmpleadoInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Empleado created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Empleado")
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
                'nombre' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:50',
                'correo' => 'nullable|string|email|max:255|unique:empleados,correo',
                'password' => 'nullable|string|min:8', // 'password' as input, will be hashed to 'password_hash'
                'rol' => 'nullable|string|max:100',
            ]);

            // Hash the password if provided
            if (isset($validatedData['password'])) {
                $validatedData['password_hash'] = Hash::make($validatedData['password']);
                unset($validatedData['password']);
            }

            $empleado = Empleado::create($validatedData);
            return response()->json($empleado, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating empleado',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * @OA\Get(
     *     path="/api/empleados/{id}",
     *     tags={"Empleados"},
     *     summary="Get empleado by ID",
     *     description="Returns a single empleado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of empleado to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Empleado")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Empleado not found"
     *     )
     * )
     */
    public function show(Empleado $empleado)
    {
        return response()->json($empleado);
    }

    /**
     * @OA\Put(
     *     path="/api/empleados/{id}",
     *     tags={"Empleados"},
     *     summary="Update an existing empleado",
     *     description="Updates an empleado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of empleado to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EmpleadoInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empleado updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Empleado")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Empleado not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, Empleado $empleado)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'sometimes|required|string|max:255',
                'telefono' => 'sometimes|nullable|string|max:50',
                'correo' => 'sometimes|nullable|string|email|max:255|unique:empleados,correo,'.$empleado->id_empleado.',id_empleado',
                'password' => 'sometimes|nullable|string|min:8',
                'rol' => 'sometimes|nullable|string|max:100',
            ]);

            // Hash the password if provided
            if (isset($validatedData['password'])) {
                $validatedData['password_hash'] = Hash::make($validatedData['password']);
                unset($validatedData['password']);
            }

            $empleado->update($validatedData);
            return response()->json($empleado);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/empleados/{id}",
     *     tags={"Empleados"},
     *     summary="Delete an empleado",
     *     description="Deletes a single empleado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of empleado to delete",
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
     *         description="Empleado not found"
     *     )
     * )
     */
    public function destroy(Empleado $empleado)
    {
        try {
            $empleado->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
