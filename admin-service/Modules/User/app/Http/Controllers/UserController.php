<?php

namespace Modules\User\Http\Controllers;

use Modules\User\Http\Controllers\MainController;
use Illuminate\Http\Request;

class UserController extends MainController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = request('remember');

        if (! auth()->guard('admin')->attempt(request(['email', 'password']), $remember)) {
            return response()->json([
                "status" => 404,
                "message" => trans('user::messages.validation.admin-notfound')
            ]);
        }

        if (! auth()->guard('admin')->user()->status) {
            auth()->guard('admin')->logout();

            return response()->json([
                "status" => 401,
                "message" => trans('user::messages.validation.activate-warning')
            ]);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
