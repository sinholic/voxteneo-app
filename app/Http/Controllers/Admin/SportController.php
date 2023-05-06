<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class SportController extends Controller
{
    public $name           =   'Sport';
    public $back_from_list =   'sports.index';
    public $back_from_form =   'sports.index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $datas = Http::withToken(session()->get('user')['token'])
        ->get(env('API_URL', null) . "/api/v1/sport-events", [
            "page" => $request->page ?? 1,
            "perPage" => $request->perPage ?? 10,
        ])->json();
        $contents = array(
            array(
                'field'     =>  'eventDate',
                'label'     =>  'Date',
            ),
            array(
                'field'     =>  'eventName',
                'label'     =>  'Name',
            ),
            array(
                'field'     =>  'eventType',
                'label'     =>  'Type',
            ),
            array(
                'field'     =>  'organizer',
                'label'     =>  'Organizer Name',
                'key'       =>  'organizerName'
            ),
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
        $datas = Http::withToken(session()->get('user')['token'])
        ->get(env('API_URL', null) . "/api/v1/organizers")->json();

        if ($datas->getStatusCode() != 200) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($datas->json()));
        }
        // proses array dengan array_map()
        $newData = array_reduce($datas['data'], function($result, $item) {
            $result[$item['id']] = $item['organizerName'];
            return $result;
        }, []);
        $contents = array(
            array(
                'field'     =>  'organizerId',
                'label'     =>  'Organizer Name',
                'type'      =>  'select2',
                'data'      =>  $newData
            ),
            array(
                'field'     =>  'eventDate',
                'label'     =>  'Date',
                'type'      =>  'date'
            ),
            array(
                'field'     =>  'eventName',
                'label'     =>  'Name',
                'type'      =>  'text'
            ),
            array(
                'field'     =>  'eventType',
                'label'     =>  'Type',
                'type'      =>  'text'
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
        $request->validate([
            'organizerId'  =>  'required',
            'eventDate'  =>  'required',
            'eventName'  =>  'required',
            'eventType'  =>  'required',
        ]);

        $response = Http::withToken(session()->get('user')['token'])
        ->post(env('API_URL', null) . "/api/v1/sport-events", [
            "organizerId" => $request->organizerId,
            "eventDate" => $request->eventDate,
            "eventName" => $request->eventName,
            "eventType" => $request->eventType,
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
        ->get(env('API_URL', null) . "/api/v1/sport-events/{$id}");

        if ($response->getStatusCode() != 200) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
        }
        $contents   = array(
            array(
                'field'     =>  'organizer',
                'label'     =>  'Organizer Name',
                'type'      =>  'text',
                'key'       =>  'organizerName',
                'state'     =>  'disabled',
                'value'     =>  $response->json()['organizer']['organizerName']
            ),
            array(
                'field'     =>  'eventDate',
                'label'     =>  'Date',
                'type'      =>  'date',
                'state'     =>  'disabled',
            ),
            array(
                'field'     =>  'eventName',
                'label'     =>  'Name',
                'type'      =>  'text',
                'state'     =>  'disabled',
            ),
            array(
                'field'     =>  'eventType',
                'label'     =>  'Type',
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
        ->get(env('API_URL', null) . "/api/v1/sport-events/{$id}");


        if ($response->getStatusCode() != 200) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
        }

        $datas = Http::withToken(session()->get('user')['token'])
        ->get(env('API_URL', null) . "/api/v1/organizers")->json();

        if ($response->getStatusCode() != 200) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
        }
        // proses array dengan array_map()
        $newData = array_reduce($datas['data'], function($result, $item) {
            $result[$item['id']] = $item['organizerName'];
            return $result;
        }, []);
        $contents   = array(
            array(
                'field'     =>  'organizerId',
                'label'     =>  'Organizer Name',
                'type'      =>  'select2',
                'data'      =>  $newData
            ),
            array(
                'field'     =>  'eventDate',
                'label'     =>  'Date',
                'type'      =>  'date',
            ),
            array(
                'field'     =>  'eventName',
                'label'     =>  'Name',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'eventType',
                'label'     =>  'Type',
                'type'      =>  'text', 
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
        $response = Http::withToken(session()->get('user')['token'])
        ->put(env('API_URL', null) . "/api/v1/sport-events/{$id}", [
            "organizerId" => $request->organizerId,
            "eventDate" => $request->eventDate,
            "eventName" => $request->eventName,
            "eventType" => $request->eventType,
        ]);
        if ($response->getStatusCode() != 204) {
            return redirect()->route($this->back_from_form)->withDanger(json_encode($response->json()));
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
        ->delete(env('API_URL', null) . "/api/v1/sport-events/{$id}");
        if ($response->getStatusCode() != 204) {
            return redirect()->route($this->back_from_list)->withDanger($response->json());
        }
        return redirect()->route($this->back_from_list)->withSuccess("$this->name has been Deleted Successfully");
    }
}
