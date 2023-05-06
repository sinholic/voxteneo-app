<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public $name           =   'User';
    public $back_from_list =   'users.index';
    public $back_from_form =   'users.index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Http::withToken(session()->get('user')['token'])
        ->get(env('API_URL', null) . "/api/v1/users/". session()->get('user')['id']);
        if ($response->getStatusCode() != 200) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
        }
        $datas = [
            "data" => [
                $response->json()
            ],
        ];
        $contents = array(
            array(
                'field'     =>  'firstName',
                'label'     =>  'First Name',
            ),
            array(
                'field'     =>  'lastName',
                'label'     =>  'Last Name',
            ),
            array(
                'field'     =>  'email',
                'label'     =>  'Email',
            )
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
            'enable_add'                =>  true,
            'enable_delete'             =>  true,
            'enable_edit'               =>  true,
            'enable_action'             =>  true
        );
        return view('page.content.index')
        ->with('datas', $datas)
        ->with('contents', $contents)
        ->with('view_options', $view_options);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contents   = array(
            array(
                'field'     =>  'firstName',
                'label'     =>  'First Name',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'lastName',
                'label'     =>  'Last Name',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'email',
                'label'     =>  'Email',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'password',
                'label'     =>  'Password',
                'type'      =>  'password',
            ),
            array(
                'field'     =>  'confirmed',
                'label'     =>  'Confirm Password',
                'type'      =>  'password',
            ),
        );
        return view('page.content.add')
        ->with('contents', $contents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                'confirmed'     => 'required|same:password'
            ]
        );

        $response = Http::post(env('API_URL', null) . "/api/v1/users", [
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "email" => $request->email,
            "password" => $request->password,
            "repeatPassword" => $request->confirmed,
        ]);

        if ($response->getStatusCode() != 200) {
            return back()->withErrors(json_encode($response->json()));
        }
        return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Added Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = Http::withToken(session()->get('user')['token'])
        ->get(env('API_URL', null) . "/api/v1/users/{$id}");

        if ($response->getStatusCode() != 200) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
        }
        $contents   = array(
            array(
                'field'     =>  'firstName',
                'label'     =>  'First Name',
                'type'      =>  'text',
                'state'     =>  'disabled',
            ),
            array(
                'field'     =>  'lastName',
                'label'     =>  'Last Name',
                'type'      =>  'text',
                'state'     =>  'disabled',
            ),
            array(
                'field'     =>  'email',
                'label'     =>  'Email',
                'type'      =>  'text',
                'state'     =>  'disabled',
            ),
        );
        return view('page.content.edit')
        ->with('model', $response->json())
        ->with('contents', $contents)
        ->with('edit', false);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = Http::withToken(session()->get('user')['token'])
        ->get(env('API_URL', null) . "/api/v1/users/{$id}");

        if ($response->getStatusCode() != 200) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
        }
        $contents   = array(
            array(
                'field'     =>  'firstName',
                'label'     =>  'First Name',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'lastName',
                'label'     =>  'Last Name',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'email',
                'label'     =>  'Email',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'oldPassword',
                'label'     =>  'Old Password',
                'type'      =>  'password',
            ),
            array(
                'field'     =>  'newPassword',
                'label'     =>  'New Password',
                'type'      =>  'password',
            ),
            array(
                'field'     =>  'confirmed',
                'label'     =>  'Confirm New Password',
                'type'      =>  'password',
            ),
        );
        return view('page.content.edit')
        ->with('model', $response->json())
        ->with('contents', $contents)
        ->with('edit', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate(
            [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
            ]
        );

        $response = Http::withToken(session()->get('user')['token'])
        ->put(env('API_URL', null) . "/api/v1/users/{$id}", [
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "email" => $request->email,
        ]);
        if ($response->getStatusCode() != 204) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
        }
        if ($request->oldPassword || $request->newPassword || $request->confirmed) {
            $request->validate(
                [
                    'oldPassword' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                    'newPassword' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                    'confirmed'     => 'required|same:newPassword'
                ]
            );
            $response = Http::withToken(session()->get('user')['token'])
            ->put(env('API_URL', null) . "/api/v1/users/{$id}/password", [
                "oldPassword" => $request->oldPassword,
                "newPassword" => $request->newPassword,
                "repeatPassword" => $request->confirmed,
            ]);
            if ($response->getStatusCode() != 204) {
                return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
            }
        }
        return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = Http::withToken(session()->get('user')['token'])
        ->delete(env('API_URL', null) . "/api/v1/users/{$id}");
        if ($response->getStatusCode() != 204) {
            return redirect()->route($this->back_from_list)->withDanger(json_encode($response->json()));
        }
        session()->flush();
        return redirect()->route($this->back_from_list)->withSuccess("$this->name has been Deleted Successfully");
    }
}
