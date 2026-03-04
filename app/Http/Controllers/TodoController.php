<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todo = Todo::all();
        return response()->json([
            'data' => $todo, 
            'message' => 'Liste récupérée'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|in:low,medium,high'
        ]);

        $todo = Todo::create($validated);

        return response()->json([
            'data' => $todo, 
            'message' => 'Tâche créée'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'message' => 'Tâche non trouvée'
            ], 404);
        }
        
        return response()->json([
            'data' => $todo, 
            'message' => 'Tâche trouvée'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'message' => 'Tâche non trouvée'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
            'priority' => 'sometimes|in:low,medium,high'
        ]);

        $todo->update($validated);

        return response()->json([
            'data' => $todo, 
            'message' => 'Tâche modifiée'
        ], 200);
    }

    /**
     * Mark the specified resource as completed.
     */
    public function complete($id) {
        $todo = Todo::find($id);

        if (!$todo) { 
            return response()->json([
                'message' => 'Tâche non trouvée'
            ], 404);
        }

        $todo->update(['completed' => true]);

        return response()->json([
            'data' => $todo, 
            'message' => 'Tâche complétée'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'message' => 'Tâche non trouvée'
            ], 404);
        }

        $todo->delete();

        return response()->json(null, 204);
    }
}
