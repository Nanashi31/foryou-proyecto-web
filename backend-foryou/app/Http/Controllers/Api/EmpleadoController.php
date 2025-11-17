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
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Empleado::all());
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        return response()->json($empleado);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
