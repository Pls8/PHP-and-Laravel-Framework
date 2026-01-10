<?php
namespace App\Http\Controllers;

use App\Interfaces\TodoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TodoController extends Controller
{
    private TodoRepositoryInterface $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function index()
    {
        try {
            $todos = $this->todoRepository->getAll();
            return view('todos.index', compact('todos'));
        } catch (\Exception $e) {
            Log::error('Error fetching todos: ' . $e->getMessage());
            return back()->with('error', 'Unable to load todos. Please try again.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|min:3',
        ]);

        try {
            $this->todoRepository->create($validated);

            return redirect()->route('todos.index')
                ->with('success', 'Todo created successfully!');
                
        } catch (\InvalidArgumentException $e) {

            return back()->withErrors(['title' => $e->getMessage()]);
        } catch (\Exception $e) {

            Log::error('Error creating todo: ' . $e->getMessage());
            return back()->with('error', 'Failed to create todo. Please try again.');
        }
    }
    public function destroy($id)
    {
        try {
            $success = $this->todoRepository->delete((int)$id);
            
            if ($success) {
                return redirect()->route('todos.index')
                    ->with('success', 'Todo deleted successfully!');
            } else {
                return redirect()->route('todos.index')
                    ->with('error', 'Todo not found.');
            }
                    
        } catch (\Exception $e) {
            Log::error('Error deleting todo: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete todo. Please try again.');
        }
    }
}