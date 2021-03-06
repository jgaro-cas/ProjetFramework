<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentDetailResource;
use App\Http\Resources\DepartmentOverviewResource;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Throwable;
use App\Http\Controllers\UserController;
use App\Traits\AuthTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DepartmentController extends Controller
{
  use AuthTrait, AuthorizesRequests;

  private $notAllowed = 'You don\'t have the rights to perform this action';

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    /* Check the user rights according to related policies, if not return a brutal 403 error */
    try {
      $this->authorize('viewAny', [Department::class, $this->userGrade()]);
    }
    catch (Throwable $e){
      return response($this->notAllowed, 403);
    }

    /* Show all */
    $departments = DepartmentOverviewResource::collection(Department::all());
    return Inertia::render('Department/DepartmentsList', ['departments' => $departments,
                                                          'hasAuth' => $this->authForDepartments()]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    /* Check the user rights according to related policies, if not return a brutal 403 error */
    try {
      $this->authorize('create', [Department::class, $this->userGrade()]);
    }
    catch (Throwable $e){
      return response($this->notAllowed, 403);
    }

    /* Validaiton */
    $validator = Validator::make($request->all(), [
      'name' => 'required|min:1|max:255',
      'leader' => 'sometimes|nullable|exists:users,id',
    ]);

    /* Store */
    if ($validator->fails()) {
      $result = response('Validation fail : ' . $validator->failed(), 500);
    }
    else {
      try {
        $department = new Department;

        if ($request->has('leader')) {
          $department->user_id = $request->input('leader');
        }

        $department->name = $request->input('name');
        $department->save();
        $result = DepartmentOverviewResource::collection(Department::all());
      }
      catch (Throwable $e){
        $result = response('Error during department creation', 500);
      }
      return $result;
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function show(Department $department)
  {
    /* Check the user rights according to related policies, if not return a brutal 403 error */
    try {
      $this->authorize('view', [$department, $this->userGrade()]);
    }
    catch (Throwable $e){
      return response($this->notAllowed, 403);
    }

    /* Show */
    $departmentWithDetails = DepartmentDetailResource::collection(Department::where('id', $department->id)->get());
    return Inertia::render('Department/DepartmentDetail', ['departmentDetail' => $departmentWithDetails,
                                                            'hasAuth' => $this->authForDepartments()]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Department $department)
  {

    /* Check the user rights according to related policies, if not return a brutal 403 error */
    try {
      $this->authorize('update', [$department, $this->userGrade()]);
    }
    catch (Throwable $e){
      return response($this->notAllowed, 403);
    }

    /* Validation */
    $validator = Validator::make($request->all(), [
      'leader' => 'sometimes|nullable|exists:users,id',
      'name' => 'sometimes|required|min:1|max:255'
    ]);

    /* Update */
    if ($validator->fails()) {
      $result = response('Validation fail : ' . $validator->failed(), 500);
    }
    else {
      try {
        if ($request->has('leader')) {
          $department->user_id = $request->input('leader');
        }
        if ($request->has('name')) {
          $department->name = $request->input('name');
        }
        $department->save();
        $result = DepartmentDetailResource::collection(Department::where('id', $department->id)->get());
      }
      catch (Throwable $e) {
        $result = response('Error during department update', 500);
      }
    }
    return $result;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function destroy(Department $department)
  {
    /* Check the user rights according to related policies, if not return a brutal 403 error */
    try {
      $this->authorize('delete', [$department, $this->userGrade()]);
    }
    catch (Throwable $e){
      return response($this->notAllowed, 403);
    }

    /* Delete */
    try{
      UserController::removeMembersFromDepartment($department->id);
      $department->delete();
      $result = response('OK', 200);
    }
    catch (Throwable $e) {
      $result = response('Error during department suppression', 500);
    }

    return $result;

  }
}
