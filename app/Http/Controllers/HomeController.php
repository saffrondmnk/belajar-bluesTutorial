<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// use Dotenv\Validator;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    
    public function dashboard()
    {
        return view('dashboard');
    }

    public function index()
    {
        $data = User::get();
        return view('index', compact('data'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = FacadesValidator::make($request->all(),
        [
            'email'     =>  'required|email',
            'nama'      =>  'required',
            'password'  =>  'required',
        ]);

        if($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data ['email']     = $request->email;
        $data ['name']      = $request->nama;
        $data ['password']  = Hash::make($request->password);

        User::create($data);

        return redirect()->route('admin.index');
    }

    public function edit(Request $request, $id)
    {
        $data = User::find($id);

        return view('edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $validator = FacadesValidator::make($request->all(),
        [
            'email'     =>  'required|email',
            'nama'      =>  'required',
            'password'  =>  'nullable',
        ]);

        if($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data ['email']     = $request->email;
        $data ['name']      = $request->nama;

        if($request->password){
            $data ['password']  = Hash::make($request->password);
        }

        User::whereId($id)->update($data);

        return redirect()->route('admin.index');

    }

    public function delete(Request $requset, $id)
    {
        $data = User::find($id);

        if($data){
            $data->delete();
        }

        return redirect()->route('admin.index');
    }
}
