<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;


// Resource routes for todos (CRUD operations)
// What: Creates multiple routes for CRUD operations
// Why: Standard RESTful routes for resource
// How: Route::resource() creates 7 standard routes
Route::resource('todos', TodoController::class)->only([
    'index',    // GET /todos - show list
    'store',    // POST /todos - create new
    'destroy',  // DELETE /todos/{id} - delete
]);

// Alternative: Manual routes (more explicit)
// Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
// Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
// Route::delete('/todos/{id}', [TodoController::class, 'destroy'])->name('todos.destroy');


Route::get('/', function () {
    return redirect()->route('todos.index');
});
