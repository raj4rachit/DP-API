<?php

declare(strict_types=1);

namespace Modules\V1\Subscription\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\V1\Subscription\Models\Subscription;
use Modules\V1\Subscription\Resources\SubscriptionResource;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
use Shared\Helpers\ResponseHelper;

final class SubscriptionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/subscription",
     *     summary="Get list of all Subscriptions",
     *     tags={"Subscriptions"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Subscription data getting successfully",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/SubscriptionResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     * Get all Subscriptions
     */
    public function index(): JsonResponse
    {
        try {
            $subscriptions = Subscription::with('user','package','doctor')->get();

            return ResponseHelper::success(SubscriptionResource::collection($subscriptions), 'Subscription data getting successfully. ');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/subscription",
     *     summary="Create a new Subscription",
     *     operationId="createSubscription",
     *     tags={"Subscriptions"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Subscription with the provided information.",
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
     *                  @OA\Property(property="total_patients",type="string", example="10"),
     *                  @OA\Property(property="patient_charge",type="string", example="100"),
     *                  @OA\Property(property="is_default",type="string", example="1"),
     *                  @OA\Property(property="status",type="string", example="Active"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Subscription created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Subscription created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/SubscriptionResource"),
     *         )
     *     ),
     *
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function store(SubscriptionCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Subscription Creation
            $subscription = new Subscription();
            $subscription->name = $request->name;
            if ('' !== $request->description) {
                $subscription->description = $request->description;
            }
            $subscription->save();

            DB::commit();

            return ResponseHelper::success(new SubscriptionResource($subscription), 'Subscription created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/subscription/{id}",
     *     summary="Show a specific Subscription",
     *     operationId="showSubscription",
     *     tags={"Subscriptions"},
     *     description="Fetch details of a Subscription by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Subscription to show",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Subscription details",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Subscription data fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/SubscriptionResource"),
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
     *             @OA\Property(property="message", type="string", example="Invalid Subscription ID"),
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
            $subscription = Subscription::findOrFail($id);
            if ( ! $subscription) {
                return ResponseHelper::error('Subscription not found');
            }

            return ResponseHelper::success(new SubscriptionResource($subscription), 'Subscription data fetched successfully');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/subscription/{id}",
     *     summary="Update a specific Subscription",
     *     operationId="updateSubscription",
     *     tags={"Subscriptions"},
     *     description="Update the details of a Subscription by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Subscription to update",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated Subscription data",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="My company", description="Subscription name"),
     *                  @OA\Property(property="description", type="string", example="safsdfsf", description="Subscription description"),
     *                  @OA\Property(property="total_patients",type="string", example="10"),
     *                  @OA\Property(property="patient_charge",type="string", example="100"),
     *                  @OA\Property(property="is_default",type="string", example="1"),
     *                  @OA\Property(property="status",type="string", example="Active"),
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Subscription updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SubscriptionResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:subscriptions,name,' . $id . ',uuid',
                'description' => 'nullable|string',
                'total_patients' => 'required|numeric',
                'patient_charge' => 'required|numeric',
                'is_default' => 'required|numeric|in:0,1',
                'status' => 'required|string|in:Active,Inactive,Canceled',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            DB::beginTransaction();
            $subscription = Subscription::findOrFail($id);
            if ( ! $subscription) {
                return ResponseHelper::error('Subscription not found');
            }

            $subscription->update($request->all());
            DB::commit();

            return ResponseHelper::success(new SubscriptionResource($subscription), 'Subscription updated successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/subscription/{id}",
     *     summary="Delete a specific Subscription",
     *     operationId="deleteSubscription",
     *     tags={"Subscriptions"},
     *     description="Delete a Subscription by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Subscription to be deleted",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Subscription deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $subscription = Subscription::findOrFail($id);
            if ( ! $subscription) {
                return ResponseHelper::error('Subscription not found');
            }

            $subscription->delete();
            DB::commit();

            return ResponseHelper::success(null, 'Subscription deleted successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
