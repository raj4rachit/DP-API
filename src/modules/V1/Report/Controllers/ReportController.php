<?php

declare(strict_types=1);

namespace Modules\V1\Report\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\V1\Report\Models\Report;
use Modules\V1\Report\Requests\ReportCreateRequest;
use Modules\V1\Report\Resources\ReportResource;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
use Shared\Helpers\ResponseHelper;

final class ReportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/report",
     *     summary="Get list of all Reports",
     *     tags={"Reports"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Report data getting successfully",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/ReportResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     * Get all Reports
     */
    public function index(): JsonResponse
    {
        try {
            $reports = Report::all();

            return ResponseHelper::success(ReportResource::collection($reports), 'Report data getting successfully. ');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/report",
     *     summary="Create a new Report",
     *     operationId="createReport",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Report with the provided information.",
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
     *                  @OA\Property(property="description",type="string", example="Asddfdsfererer"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Report created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Report created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/ReportResource"),
     *         )
     *     ),
     *
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function store(ReportCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Report Creation
            $report = new Report();
            $report->name = $request->name;
            if ('' !== $request->description) {
                $report->description = $request->description;
            }
            $report->save();

            DB::commit();

            return ResponseHelper::success(new ReportResource($report), 'Report created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/report/{id}",
     *     summary="Show a specific Report",
     *     operationId="showReport",
     *     tags={"Reports"},
     *     description="Fetch details of a Report by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Report to show",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Report details",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Report data fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/ReportResource"),
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
     *             @OA\Property(property="message", type="string", example="Invalid Report ID"),
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
            $report = Report::findOrFail($id);
            if ( ! $report) {
                return ResponseHelper::error('Report not found');
            }

            return ResponseHelper::success(new ReportResource($report), 'Report data fetched successfully');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/report/{id}",
     *     summary="Update a specific Report",
     *     operationId="updateHospital",
     *     tags={"Reports"},
     *     description="Update the details of a Report by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Report to update",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated Report data",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="My company", description="Report name"),
     *                  @OA\Property(property="description", type="string", example="safsdfsf", description="Report description),
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Report updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ReportResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Report not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:reports,name,' . $id . ',uuid',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            DB::beginTransaction();
            $report = Report::findOrFail($id);
            if ( ! $report) {
                return ResponseHelper::error('Report not found');
            }

            $report->update($request->all());
            DB::commit();

            return ResponseHelper::success(new ReportResource($report), 'Report updated successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/report/{id}",
     *     summary="Delete a specific Report",
     *     operationId="deleteReport",
     *     tags={"Reports"},
     *     description="Delete a Report by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Report to be deleted",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Report deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Report not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $report = Report::findOrFail($id);
            if ( ! $report) {
                return ResponseHelper::error('Report not found');
            }

            $report->delete();
            DB::commit();

            return ResponseHelper::success(null, 'Report deleted successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
