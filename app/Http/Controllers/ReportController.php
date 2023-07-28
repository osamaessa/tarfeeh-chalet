<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Traits\ErrorResponseTrait;
use Exception;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ErrorResponseTrait;

    public function add(Request $request)
    {
        try {

            $fields = $request->validate([
                'name' => 'required',
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'title' => 'required',
                'message' => 'required',
            ]);
            $report = Report::create([
                'name' => $fields['name'],
                'phone' => $fields['phone'],
                'title' => $fields['title'],
                'message' => $fields['message'],
            ]);

            return new ReportResource($report);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function solve(Request $request)
    {
        try {

            $fields = $request->validate([
                'id' => 'required',
            ]);
            $report = Report::find($fields['id']);
            if(!$report){
                return $this->badRequest(Messages::REPORT_NOT_FOUND);
            }

            $report->is_solved = true;
            $report->save();
            return new ReportResource($report);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {

            $isSolved = false;
            if($request->has('solved')){
                $isSolved = $request->boolean('solved');
            }
            
            $reports = Report::where('is_solved','=',$isSolved);

            return ReportResource::collection($reports->simplePaginate());
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
}
