<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Models
use App\Skill;
use App\Worker;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $req, int $id)
    {
      // is valid id
      if ($id<1) {
        return \Response::json([
          'status' => 1,
          'message' => 'invalid id param',
          'data' => ''
        ], 422);
      }

      // Validate data inputs
      $valid = \Validator::make($req->all(), [
        'name' => 'required|string',
        'level' => 'required|integer|between:1,5'
      ]);

      if ($valid->fails()) {
        return \Response::json([
          'status' => 2,
          'message' => $valid->errors(),
          'data' => ''
        ], 422);
      }

      // Fisrt check if worker exists
      if(Worker::where('id', $id)
               ->where('status', 1)->exists()<1) {
        return \Response::json([
          'status' => 3,
          'message' => 'Worker not found or disabled',
          'data' => ''
        ], 404);
      }

      // Store new skill
      $skill = Skill::create([
        'name_skill' => $req->name,
        'level' => $req->level,
        'worker_id' => $id
      ]);

      // Response
      if ($skill->id>0) {
        return \Response::json([
          'status' => 0,
          'message' => 'ok',
          'data' => $skill
        ], 200);
      }
      else {
        return \Response::json([
          'status' => 4,
          'message' => 'cannot save skill',
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
      // Is id param valid
      if ($id>0) {
        $skill = Skill::find($id);
        // Check if skill exists
        if (is_object($skill)) {
          // Delete skill
          if ($skill->delete()) {
            return \Response::json([
              'status' => 0,
              'message' => 'ok skill deleted',
              'data' => ''
            ], 300);
          }
          else {
            return \Response::json([
              'status' => 3,
              'message' => 'cannot delete skill',
              'data' => ''
            ], 500);
          }
        }
        else {
          return \Response::json([
            'status' => 2,
            'message' => 'skill not found',
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
