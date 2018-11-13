<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

// Models
use App\Worker;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $workers = Worker::where('status', 1)
                       ->with('Skills')
                       ->get();
      if (count($workers)>0) {
        return \Response::json([
          'status' => 0,
          'message' => 'ok',
          'data' => $workers
        ], 200);
      }
      else {
        return \Response::json([
          'status' => 1,
          'message' => 'not found',
          'data' => ''
        ], 404);
      }
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
      // Validate data inputs
      $valid = \Validator::make($req->all(), [
        'name' => 'required|string',
        'email' => 'required|email|unique:workers,email',
        'job' => 'required|string',
        'birthdate' => 'required|date_format:"d/m/Y"',
        'residence' => 'required|string',
      ]);

      if ($valid->fails()) {
        return \Response::json([
          'status' => 1,
          'message' => $valid->errors(),
          'data' => ''
        ], 422);
      }

      // Create new worker
      $worker = Worker::create([
        'name' => $req->name,
        'email' => $req->email,
        'job' => $req->job,
        'birthdate' => Carbon::parse($req->birthdate)->format('Y-m-d'),
        'residence' => $req->residence
      ]);

      // Response
      if ($worker->id>0) {
        return \Response::json([
          'status' => 0,
          'message' => 'ok worker saved',
          'data' => $worker
        ], 200);
      }
      else {
        return \Response::json([
          'status' => 2,
          'message' => 'cannot save worker',
          'data' => ''
        ], 500);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
      // is valid id param
      if ($id>0) {
        $worker = Worker::with('Skills')->find($id);

        // check if worker exists
        if (is_object($worker)) {
          return \Response::json([
            'status' => 1,
            'message' => 'ok',
            'data' => $worker
          ], 200);
        }
        else {
          return \Response::json([
            'status' => 2,
            'message' => 'worker not found',
            'data' => ''
          ], 404);
        }
      }
      else {
        return \Response::json([
          'status' => 1,
          'message' => 'invlaid param id',
          'data' => ''
        ], 422);
      }
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, int $id)
    {
      // is valid id param
      if ($id>0) {

        // Validate data inputs, only name is required at least
        $valid = \Validator::make($req->all(), [
          'name' => 'required|string',
          'email' => [
              'nullable',
              'email',
              Rule::unique('workers', 'email')->ignore($id, 'id')
            ],
          'job' => 'nullable|string',
          'birthdate' => 'nullable|date_format:"d/m/Y"',
          'residence' => 'nullable|string',
          'status' => 'nullable|integer'
        ]);

        if ($valid->fails()) {
          return \Response::json([
            'status' => 2,
            'message' => $valid->errors(),
            'data' => ''
          ], 422);
        }

        // Get worker if exists
        $worker = Worker::where('status', 1)
                        ->find($id);

        if (is_object($worker)) {
          $worker->name = $req->name;
          $worker->email = (empty($req->email)) ? $worker->email : $req->email;
          $worker->job = (empty($req->job)) ? $worker->job : $req->job;
          $worker->birthdate = (empty($req->birthdate)) ? $worker->birthdate : $req->birthdate;
          $worker->residence = (empty($req->residence)) ? $worker->residence : $req->residence;
          $worker->status = (empty($req->status)) ? $worker->status : $req->status;

          // Store changes
          if ($worker->save()) {
            return \Response::json([
              'status' => 0,
              'message' => 'ok worker updated',
              'data' => $worker
            ], 200);
          }
          else {
            return \Response::json([
              'status' => 2,
              'message' => 'cannot update worker',
              'data' => ''
            ], 500);
          }
        }
        else {
          return \Response::json([
            'status' => 2,
            'message' => 'worker not found or disabled',
            'data' => ''
          ], 404);
        }
      }
      else {
        return \Response::json([
          'status' => 1,
          'message' => 'invlaid param id',
          'data' => ''
        ], 422);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
      if ($id>0) {
        $worker = Worker::find($id);
        // Check if worker exists
        if (is_object($worker)) {

          // Delete worker and his skills
          if ($worker->skills()->delete() && $worker->delete()) {
            return \Response::json([
              'status' => 0,
              'message' => 'worker deleted',
              'data' => ''
            ], 200);
          }
          else {
            return \Response::json([
              'status' => 3,
              'message' => 'cannot delete worker',
              'data' => ''
            ], 500);
          }
        }
        else {
          return \Response::json([
            'status' => 2,
            'message' => 'worker not found',
            'data' => ''
          ], 404);
        }
      }
      else {
        return \Response::json([
          'status' => 1,
          'message' => 'invalid id param',
          'data' => ''
        ], 422);
      }
    }
}
