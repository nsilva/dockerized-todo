<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Actions\Todo\ShowTodo;
use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Support\Enums\TodoStatusEnum;
use App\Http\Requests\CreateTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Actions\Todo\ListTodos;
use App\Http\Controllers\Actions\Todo\CreateTodo;
use App\Http\Controllers\Actions\Todo\UpdateTodo;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ListTodos $listAction)
    {
        return $listAction->listTodos();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTodoRequest $request, CreateTodo $createAction)
    {
        return $createAction->create($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo, ShowTodo $showAction)
    {
        return $showAction->show($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo, UpdateTodo $updateAction)
    {
        return $updateAction->update($request, $todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $todo = $user->todos()->find($id);

        if (!$todo) {
            return response()->json(['error' => 'Todo not found'], 404);
        }

        $todo->delete();

        return response()->json(null, 204);
    }
}
