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
            )
        );
        return view('page.content.edit')
        ->with('model', $response->json())
        ->with('contents', $contents)
        ->with('edit', false);
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
                'field'     =>  'organizerName',
                'label'     =>  'Name',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'imageLocation',
                'label'     =>  'Location',
                'type'      =>  'text',
            )
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
                'organizerName'  =>  'required',
                'imageLocation'  =>  'required',
            ]
        );

        $response = Http::withToken(session()->get('user')['token'])
        ->post(env('API_URL', null) . "/api/v1/users", [
            "organizerName" => $request->organizerName,
            "imageLocation" => $request->imageLocation,
        ]);
        if ($response->getStatusCode() != 200) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
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
                'field'     =>  'organizerName',
                'label'     =>  'Name',
                'type'      =>  'text',
                'state'     =>  'disabled',
            ),
            array(
                'field'     =>  'imageLocation',
                'label'     =>  'Location',
                'type'      =>  'text',
                'state'     =>  'disabled',
            )
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
                'field'     =>  'organizerName',
                'label'     =>  'Name',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'imageLocation',
                'label'     =>  'Location',
                'type'      =>  'text',
            )
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
        $response = Http::withToken(session()->get('user')['token'])
        ->put(env('API_URL', null) . "/api/v1/users/{$id}", [
            "organizerName" => $request->organizerName,
            "imageLocation" => $request->imageLocation,
        ]);
        if ($response->getStatusCode() != 204) {
            return redirect()->route($this->back_from_form)->withDanger($response->json());
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
            return redirect()->route($this->back_from_list)->withDanger($response->json());
        }
        return redirect()->route($this->back_from_list)->withSuccess("$this->name has been Deleted Successfully");
    }
}
