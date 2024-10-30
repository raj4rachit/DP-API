<?php

declare(strict_types=1);

namespace Modules\V1\Lab\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\V1\Lab\Models\Lab;
use Modules\V1\Lab\Models\LabReport;
use Modules\V1\Lab\Requests\LabCreateRequest;
use Modules\V1\Lab\Resources\LabResource;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
use Shared\Helpers\ResponseHelper;

final class LabController extends Controller
{
    /**
     * @OA\Get(
     *     path="/lab",
     *     summary="Get list of all Labs",
     *     tags={"Labs"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lab data getting successfully",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/LabResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     * Get all devices
     */
    public function index(): JsonResponse
    {
        try {
            $labs = Lab::with('user', 'reports')->get();

            return ResponseHelper::success(LabResource::collection($labs), 'Lab data getting successfully. ');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/lab",
     *     summary="Create a new Lab",
     *     operationId="createLab",
     *     tags={"Labs"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Lab with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="test name"),
     *                  @OA\Property(property="address", type="string", example="JohnDoe"),
     *                  @OA\Property(property="phone", type="string", example="1231231231"),
     *                  @OA\Property(property="user_id",type="string", example="hos-gmail.com"),
     *                  @OA\Property(
     *                      property="reports",
     *                      type="array",
     *
     *                      @OA\Items(type="string"),
     *                      description="lab reports"
     *                  )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Lab created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Lab created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/LabResource"),
     *         )
     *     ),
     *
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function store(LabCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Lab Creation
            $lab = new Lab();
            $lab->name = $request->name;
            $lab->address = $request->address;
            $lab->phone = $request->phone;
            $lab->user_id = $request->user_id;
            $lab->save();

            if (count($request->reports)) {
                foreach ($request->reports as $report) {
                    $data = new LabReport();
                    $data->lab_id = $lab->uuid;
                    $data->report_id = $report;
                    $data->save();
                }
            }

            DB::commit();

            return ResponseHelper::success(new LabResource($lab), 'Lab created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/lab/{id}",
     *     summary="Show a specific Lab",
     *     operationId="showLab",
     *     tags={"Labs"},
     *     description="Fetch details of a Lab by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Lab to show",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lab details",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Lab data fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/LabResource"),
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized access"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=401)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Invalid Lab ID"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=422)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="An error occurred on the server"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=500)
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $lab = Lab::with('user', 'reports')->findOrFail($id);
            if ( ! $lab) {
                return ResponseHelper::error('Lab not found');
            }

            return ResponseHelper::success(new LabResource($lab), 'Lab data fetched successfully');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/lab/{id}",
     *     summary="Update a specific Lab",
     *     operationId="updateLab",
     *     tags={"Labs"},
     *     description="Update the details of a Lab by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Lab to update",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated Lab data",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="test name"),
     *                   @OA\Property(property="address", type="string", example="JohnDoe"),
     *                   @OA\Property(property="phone", type="string", example="1231231231"),
     *                   @OA\Property(property="user_id",type="string", example="hos-gmail.com"),
     *                   @OA\Property(
     *                       property="reports",
     *                       type="array",
     *
     *                       @OA\Items(type="string"),
     *                       description="lab reports"
     *                   )
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lab updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/LabResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Lab not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:labs,name,' . $id . ',uuid',
                'address' => 'required|string',
                'phone' => 'nullable|string|max:20',
                'user_id' => 'required|string|exists:users,uuid',
                'reports' => 'required|array|exists:reports,uuid',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            DB::beginTransaction();
            $lab = Lab::findOrFail($id);
            if ( ! $lab) {
                return ResponseHelper::error('Lab not found');
            }

            $labReports = LabReport::where('lab_id', $id)->get();
            if(!$labReports->isEmpty()) {
                LabReport::where('lab_id', $id)->delete();
            }
            $lab->update($request->all());

            if (count($request->reports)) {
                foreach ($request->reports as $report) {
                    $data = new LabReport();
                    $data->lab_id = $id;
                    $data->report_id = $report;
                    $data->save();
                }
            }

            DB::commit();
            return ResponseHelper::success(new LabResource($lab), 'Lab updated successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/lab/{id}",
     *     summary="Delete a specific Lab",
     *     operationId="deleteLab",
     *     tags={"Labs"},
     *     description="Delete a Lab by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Lab to be deleted",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Lab deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lab not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $lab = Lab::findOrFail($id);
            if ( ! $lab) {
                return ResponseHelper::error('Lab not found');
            }

            $lab->delete();
            DB::commit();

            return ResponseHelper::success(null, 'Lab deleted successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
