<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Cliente::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'usuario' => 'nullable|string|max:255|unique:clientes,usuario',
                'password' => 'required|string|min:8', // 'password' as input, will be hashed to 'password_hash'
                'telefono' => 'nullable|string|max:50',
                'domicilio' => 'nullable|string|max:255',
                'correo' => 'nullable|string|email|max:255|unique:clientes,correo',
                'auth_user_id' => 'nullable|uuid',
            ]);

            // Hash the password
            $validatedData['password_hash'] = Hash::make($validatedData['password']);
            unset($validatedData['password']); // Remove plain password from data

            $cliente = Cliente::create($validatedData);
            return response()->json($cliente, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating cliente',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        return response()->json($cliente);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'sometimes|required|string|max:255',
                'usuario' => 'sometimes|nullable|string|max:255|unique:clientes,usuario,'.$cliente->id_cliente.',id_cliente',
                'password' => 'sometimes|required|string|min:8',
                'telefono' => 'sometimes|nullable|string|max:50',
                'domicilio' => 'sometimes|nullable|string|max:255',
                'correo' => 'sometimes|nullable|string|email|max:255|unique:clientes,correo,'.$cliente->id_cliente.',id_cliente',
                'auth_user_id' => 'sometimes|nullable|uuid',
            ]);

            if (isset($validatedData['password'])) {
                $validatedData['password_hash'] = Hash::make($validatedData['password']);
                unset($validatedData['password']);
            }

            $cliente->update($validatedData);
            return response()->json($cliente);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        try {
            $cliente->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
