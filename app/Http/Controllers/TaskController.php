<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class TaskController extends Controller
{
    // =============================================== LOGIN FUNCTIONS  ============================================= //

    public function index()
    {
        return view('Login');
    }

    public function signup(Request $request)
    {
        try {
            $user = Login::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => encrypt($request->password),
            ]);

            $user->save();
            return redirect('/')->with('success', 'Signup successful.');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Error in signup: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $user = Login::where('email', $request->email)->first();

            if (!$user || decrypt($user->password) !== $request->password) {
                return redirect('/')->with('error', 'Invalid credentials');
            }

            // Manually store user information in the session
            session()->put('user_id', $user->user_id);

            return redirect('/task_view');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Error in login: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            // Manually clear the session
            session()->remove('user_id');

            return redirect('/')->with('success', 'Logout successful');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Error in logout: ' . $e->getMessage());
        }
    }

    //========================== Login with Google ======================================

    public function loginWithGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Error in Google login: ' . $e->getMessage());
        }
    }

    public function callbackFromGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = Login::where('email', $user->email)->first();

            if (!$finduser) {
                $finduser = new Login();
                $finduser->name = $user->name;
                $finduser->email = $user->email;
                $finduser->password = encrypt("123456");
                $finduser->google_id = $user->getId();
                $finduser->save();
            }

            session()->put('user_id', $finduser->user_id);

            return redirect('/task_view');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Error in Google callback: ' . $e->getMessage());
        }
    }

    // ====================================End of LOGIN FUNCTIONS===============================================//

    // =============================Task function ============================================//

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'category_name' => 'required|string|max:255',
                'description' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,progress,completed,upcoming',
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error in creating task: ' . $e->getMessage());
        }
    }

    public function task_view(Request $request)
{
    try {
        $tasks = Task::all();
        $category = Category::all();
        $selectedStatus = session('status', '');
        $selectedDateFrom = session('date_from', '');
        $selectedDateTo = session('date_to', '');
        $selectedCat = session('cat_name', '');
        $searchCategory = $request->input('search_category', '');

        // Ensure $category is always defined
        if (!$category) {
            $category = collect();
        }

        $data = compact('tasks', 'category', 'selectedStatus', 'selectedDateFrom', 'selectedDateTo', 'selectedCat', 'searchCategory');
        return view('task')->with($data);
    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Error in task view: ' . $e->getMessage());
    }
}


    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'category_name' => 'required|string|max:255',
                'description' => 'required|string',
                'deadline' => 'required|date',
                'status' => 'required|in:pending,progress,completed,upcoming',
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error in updating task: ' . $e->getMessage());
        }
    }

    public function show(Task $task)
    {
        return view('show', compact('task'));
    }

    public function destroy(Request $request, Task $task)
    {
        try {
            $task->delete();

            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tasks.index')->with('error', 'Error in deleting task: ' . $e->getMessage());
        }
    }

    public function sorting(Request $request)
    {
        try {
            $tasks = Task::query();
            $category = Category::all();
            $searchCategory = $request->input('search_category', '');

            // Apply search filter
            if ($searchCategory !== '') {
                $tasks->where('category', 'like', '%' . $searchCategory . '%');
            }

            // Rest of your sorting logic
            if ($request->filled('status')) {
                $tasks->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $tasks->whereDate('deadline', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $tasks->whereDate('deadline', '<=', $request->date_to);
            }

            if ($request->filled('cat_name')) {
                $tasks->where('category', $request->cat_name);
            }

            $filteredTasks = $tasks->get();

            return view('task', [
                'tasks' => $filteredTasks,
                'selectedCat' => $request->filled('cat_name') ? $request->cat_name : null,
                'selectedStatus' => $request->filled('status') ? $request->status : null,
                'selectedDateFrom' => $request->filled('date_from') ? $request->date_from : null,
                'selectedDateTo' => $request->filled('date_to') ? $request->date_to : null,
                'searchCategory' => $searchCategory,
            ])->with(compact('category'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error in sorting tasks: ' . $e->getMessage());
        }
    }


    public function Cat_store(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error in creating category: ' . $e->getMessage());
        }
    }
}
