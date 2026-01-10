@extends('layouts.app')

@section('title', 'Todo List')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Todo List</h4>
            </div>
            
            <div class="card-body">
                <form action="{{ route('todos.store') }}" method="POST" class="mb-4">
                    @csrf {{-- CSRF protection token - REQUIRED for POST forms --}}
                    
                    <div class="input-group">
                        <input type="text" 
                               name="title" 
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="What needs to be done?" 
                               value="{{ old('title') }}"
                               required
                               autofocus>
                        
                        <button type="submit" class="btn btn-primary">
                            Add Todo
                        </button>
                    </div>
                    
                    @error('title')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </form>

                @if(empty($todos))
                    {{-- No todos message --}}
                    <div class="text-center py-5">
                        <p class="text-muted">No todos yet. Add one above! ✨</p>
                    </div>
                @else
                    <div class="list-group">
                        @foreach($todos as $todo)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <!-- Todo Title -->
                                    <h6 class="mb-1">{{ $todo['title'] }}</h6>
                                    
                               
                                    <small class="text-muted">
                                        Added: {{ \Carbon\Carbon::parse($todo['created_at'])->diffForHumans() }}
                                        {{-- diffForHumans(): "2 hours ago", "3 days ago", etc. --}}
                                    </small>
                                </div>
                                
                                <form action="{{ route('todos.destroy', $todo['id']) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Delete this todo?')">
                                    @csrf
                                    @method('DELETE') {{-- Method spoofing for DELETE request --}}
                                    
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="card-footer text-muted text-center">
                {{ count($todos) }} {{ Str::plural('todo', count($todos)) }}
                {{-- plural(): Adds 's' if count > 1 --}}
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>

document.addEventListener('DOMContentLoaded', function() {

    const input = document.querySelector('input[name="title"]');
    if (input) input.focus();
    

    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection