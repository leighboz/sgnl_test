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
        
        // expect exactly one parameter
        if (count($parameters)!=1) {
            return Response::json([
                'error' => 'incorrect number of parameters'    // bad request
            ],400);
        }

        // parameter should be called 'cn'
        if (!array_key_exists('cn', $parameters)) {
            return Response::json([
                'error' => 'invalid parameter name'  // bad request
            ],400);
        }

        $cn = strval($parameters['cn']);    // get cn value as string

        // from api spec, if cn value = 'not_found', empty fields should be returned
        if ($cn=='not_found') {
            return Response::json([
                'full_name' => '',
                'department' => []
            ]);
        }

        // cn value should be exactly 32 characters long
        if (strlen($cn)!=32) {
            return Response::json([
                'error' => 'card number must be 32 characters'  // bad request
            ],400);
        }

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
