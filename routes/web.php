<?php

use App\Models\Task;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//To redirect any homepage url to /tasks url
Route::get('/', function(){
  return redirect()->route('tasks.index');
});

//latest() is the sql query and we write get() to execute this query
Route::get('/tasks', function () {
    return view('index', [
      'tasks' => Task::latest()->get()
    ]);
})->name('tasks.index');

//Routes having /tasks and custom name will come before dynamic routes having /tasks
Route::view('/tasks/create', 'create')
    ->name('tasks.create');

//Route to show task details according to id
Route::get('/tasks/{id}', function ($id){
  return view('show', [
    'task' => Task::findOrFail($id)
]);
})->name('tasks.show');

//This route have same name as above route but above route have get method and it have post
Route::post('/tasks', function (Request $request) {
  $data = $request->validate([
    'title' => 'required|max:255',
    'description' => 'required',
    'long_description' => 'required'
  ]);

  $task = new Task;
  $task->title = $data['title'];
  $task->description = $data['description'];
  $task->long_description = $data['long_description'];
  $task->save();

  return redirect()->route('tasks.show', ['id' => $task->id]);
})->name('tasks.store');

