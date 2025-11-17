<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Pago::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_cot' => 'required|exists:cotizaciones,id_cotizacion',
                'metodo' => 'required|string|max:100',
                // 'fec_pago' is automatically set by the database using useCurrent()
                'cantidad' => 'required|numeric|min:0',
            ]);

            $pago = Pago::create($validatedData);
            return response()->json($pago, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating pago',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        return response()->json($pago);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        try {
            $validatedData = $request->validate([
                'id_cot' => 'sometimes|required|exists:cotizaciones,id_cotizacion',
                'metodo' => 'sometimes|required|string|max:100',
                'cantidad' => 'sometimes|required|numeric|min:0',
            ]);

            $pago->update($validatedData);
            return response()->json($pago);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        try {
            $pago->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
