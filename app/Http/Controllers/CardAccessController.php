<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AccessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CardAccessController extends Controller
{
    /**
     * Return Employee Info from Card Number
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $parameters = $request->query();
        
        // only expect one parameter
        if (count($parameters)>1) {
            return Response::json([
                'error' => 'too many parameters'    // bad request
            ],400);
        }

        // parameter should be called 'cn'
        if (!array_key_exists('cn', $parameters)) {
            return Response::json([
                'error' => 'invalid parameter'  // bad request
            ],400);
        }

        $cn = $parameters['cn'];

        $matching_card = AccessCard::where('rfid', $cn)->first();

        if (!$matching_card) {
            return Response::json([
                'full_name' => '',
                'department' => []
            ]);
        }

        $employee = Employee::find($matching_card->employee_id);

        if (!$employee) {
            return Response::json([
                'full_name' => '',
                'department' => []
            ]);
        }

        $departments = [];

        $employee->departments()->each(
            function($dept) use (&$departments) {
                array_push($departments, $dept->name);
            }
        );

        return Response::json([
            'full_name' => $employee->given_names.' '.$employee->family_name,
            'department' => $departments
        ]);
    }
}
