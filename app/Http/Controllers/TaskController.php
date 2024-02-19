<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // =============================================== LOGIN FUNCTIONS  ============================================= //

    public function index()
    {
        return view('Login');
    }

    public function signup(Request $request)
    {

        $user = Login::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => encrypt($request->password),
        ]);

        $user->save();
        echo "<script>window.alert('signup successfully')</script>";
        echo "<script>window.open('/', '_self')</script>";
    }

    public function login(Request $request)
    {
        $user = Login::where('email', $request->email)->first();

        if (!$user || decrypt($user->password) !== $request->password) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Manually store user information in the session
        session()->put('user_id', $user->user_id);

        return redirect('/task_view');
    }

    public function logout()
    {
        // Manually clear the session
        session()->remove('user_id');

        return redirect('/'); // Redirect to the login page or another route
    }


    // ====================================End of LOGIN FUNCTIONS===============================================//


    // =============================Task function ============================================//


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'status' => 'required|in:pending,progress,completed',
        ]);

        $task = new Task();
        $task->title = $request->input('title');
        $task->category = $request->input('category_name');
        $task->description = $request->input('description');
        $task->deadline = $request->input('deadline');
        $task->status = $request->input('status');
        $task->user_id = session('user_id');
        $task->save();

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function task_view(Request $request)
{
    $tasks = Task::all();
    $category = Category::all();
    $selectedStatus = session('status', '');
    $selectedDateFrom = session('date_from', '');
    $selectedDateTo = session('date_to', '');
    $selectedCat = session('cat_name', '');

    // Ensure $category is always defined
    if (!$category) {
        $category = collect();
    }

    $data = compact('tasks', 'category', 'selectedStatus', 'selectedDateFrom', 'selectedDateTo', 'selectedCat');
    return view('task')->with($data);
}





public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'category_name' => 'required|string|max:255',
        'description' => 'required|string',
        'deadline' => 'required|date',
        'status' => 'required|in:pending,progress,completed',
    ]);

    $task = Task::findOrFail($id);

    $task->update([
        'title' => $validatedData['title'],
        'category' => $validatedData['category_name'], // Check your Task model for the actual field name
        'description' => $validatedData['description'],
        'deadline' => $validatedData['deadline'],
        'status' => $validatedData['status'],
    ]);

    return redirect()->back()->with('success', 'Task updated successfully.');
}





    public function show(Task $task)
    {
        return view('show', compact('task'));
    }

    public function destroy(Request $request, Task $task)
    {

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }




    public function sorting(Request $request)
    {
        $tasks = Task::query();
        $category = Category::all();
        $data = compact('category');
        if ($request->filled('status')) {
            $tasks->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $tasks->whereDate('deadline', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $tasks->whereDate('deadline', '<=', $request->date_to);
        }
        if($request->filled('cat_name')){
            $tasks->where('category', $request->cat_name);
        }

        $filteredTasks = $tasks->get();

        return view('task', [
            'tasks' => $filteredTasks,
            'selectedCat'=> $request->filled('cat_name') ? $request->cat_name : null ,
            'selectedStatus' => $request->filled('status') ? $request->status : null,
            'selectedDateFrom' => $request->filled('date_from') ? $request->date_from : null,
            'selectedDateTo' => $request->filled('date_to') ? $request->date_to : null,
        ])->with($data);
    }




    public function Cat_store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'categoryName' => 'required|string|max:255',
        ]);

        $category = new Category([
            'Cat_name' => $request->input('categoryName'),
        ]);

        // Save the category
        $category->save();

        return redirect()->back()->with('success', 'Category created successfully');
    }
}
