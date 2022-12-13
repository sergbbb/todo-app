<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoPatchRequest;
use App\Http\Requests\TodoStoreRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = auth()->user();

        return Inertia::render('Dashboard')
            ->with([
                'todos' => TodoResource::collection($user->todos),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TodoStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TodoStoreRequest $request)
    {
        $user = auth()->user();

        $todo = new Todo();
        $todo->title = $request->get('title');

        $user->todos()->save($todo);

        $user->checkTodosLimitAndMarkAsDone();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TodoPatchRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TodoPatchRequest $request, $id)
    {
        $user = auth()->user();
        $todo = $user->todos()->find($id);
        $todo->fill($request->all())->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $user->todos()->find($id)->delete();
        return redirect()->back();
    }
}
