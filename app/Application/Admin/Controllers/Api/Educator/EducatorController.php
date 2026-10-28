<?php

namespace App\Application\Admin\Controllers\Api\Educator;

use App\Application\Educator\Requests\FetchEducatorRequest;
use App\Domain\Educator\Actions\FetchEducatorAction;
use App\Domain\Educator\Resource\EducatorResource;
use App\Domain\Educator\Resource\Profile\EducatorProfileResource;
use App\Http\Controllers\Controller;
use App\Mail\Educator\InviteEducatorMail;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Educator\Models\Educator;
use Domain\Educator\Models\EducatorAcademicMajor;
use Domain\Educator\Models\EducatorInvitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\error;

class EducatorController extends Controller
{
    use ApiResponseTrait;

    public function fetch(FetchEducatorRequest $request, FetchEducatorAction $fetchEducatorAction): JsonResponse
    {
        try {
            $educators = $fetchEducatorAction->handle($request->validated());

            return $this->responseData(data: [
                'educators' => EducatorProfileResource::collection($educators)
            ]);
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to fetch educators. Please try again.'), code: 500);
        }
    }

    public function show(Request $request, Educator $educator): JsonResponse
    {
        try {
            return $this->responseData(data: [
                'educator' => new EducatorProfileResource($educator->load(['profile', 'academicMajors'])),
            ]);
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to fetch educator profile.'), data:[$e->getMessage()], code: 500);
        }
    }

    public function updateStatus(Request $request, Educator $educator): JsonResponse
    {
        $statusInput = strtolower($request->input('status'));

        $request->merge([
            'status' => $statusInput,
        ]);

        $request->validate([
            'status' => ['required', 'string', 'in:active,inactive,pending,suspended,banned'],
        ]);

        try {
            $educator->update([
                'status' => $statusInput,
            ]);

            return $this->responseData(
                data: ['educator' => new EducatorProfileResource($educator->fresh(['profile', 'academicMajors']))],
                msg: __('The educator status has been updated successfully.')
            );
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to update educator status. Please try again.'), code: 500);
        }
    }

    public function destroy(Request $request, Educator $educator): JsonResponse
    {
        try {
            DB::transaction(function () use ($educator) {
                $educator->academicMajors()->delete();
                $educator->profile()->delete();
                $educator->delete();
            });

            return $this->responseMessage(
                message: __('The educator has been deleted successfully')
            );
        } catch (\Exception $e) {

            return $this->responseError(msg: __('Failed to delete educator. Please try again.'), code: 500);
        }
    }


    public function accept(Request $request, Educator $educator): JsonResponse
    {
        try {
            if ($educator->status === 'active') {
                return $this->responseError(msg: __('The educator is already active.'), code: Response::HTTP_CONFLICT);
            }

            if ($educator->status === 'rejected') {
                return $this->responseError(msg: __('Cannot accept a rejected educator. Please contact support.'), code: Response::HTTP_CONFLICT);
            }

            $educator->update([
                'status' => 'active',
                'is_accepted' => true,
                'suspension_reason' => null,
            ]);

            return $this->responseData(data: ['educator' => new EducatorProfileResource($educator)], msg: __('The educator has been accepted successfully'));
        } catch (\Exception $e) {

            return $this->responseError(msg: __('Failed to accept educator. Please try again.'), code: 500);
        }
    }

    public function reject(Request $request, Educator $educator): JsonResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:65535',
        ]);

        try {
            if ($educator->status === 'rejected') {
                return $this->responseError(msg: __('The educator is already rejected.'), code: Response::HTTP_CONFLICT);
            }

            if ($educator->status === 'active') {
                return $this->responseError(msg: __('Cannot reject an active educator. Please suspend instead.'), code: Response::HTTP_CONFLICT);
            }

            $educator->update([
                'status' => 'rejected',
                'is_accepted' => false,
                'rejection_reason' => $request->input('rejection_reason'),
            ]);

            return $this->responseData(data: ['educator' => new EducatorProfileResource($educator)], msg: __('The educator has been rejected successfully'));
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to reject educator. Please try again.'), code: 500);
        }
    }

    public function approveAcademicMajor(Request $request, Educator $educator, EducatorAcademicMajor $educatorMajor): JsonResponse
    {
        try {
            $academicMajor = $educator->academicMajors()->where('id', $educatorMajor->id)->first();

            if (!$academicMajor) {
                return $this->responseError(msg: __('The academic major does not exist for this educator.'), code: Response::HTTP_NOT_FOUND);
            }

            if ($academicMajor->status === 'verified') {
                return $this->responseError(msg: __('The academic major is already verified.'), code: Response::HTTP_CONFLICT);
            }

            $academicMajor->update([
                'status' => 'verified',
                'approval_document' => true,
                'rejection_reason' => null
            ]);

            return $this->responseData(data: ['academic_major' => $academicMajor], msg: __('The academic major has been verified successfully'));
        } catch (\Exception $e) {

            return $this->responseError(msg: __('Failed to approve academic major. Please try again.'), code: 500);
        }
    }

    public function rejectAcademicMajor(Request $request, Educator $educator, EducatorAcademicMajor $educatorMajor): JsonResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:65535',
        ]);

        try {
            $academicMajor = $educator->academicMajors()->where('id', $educatorMajor->id)->first();

            if (!$academicMajor) {
                return $this->responseError(msg: __('The academic major does not exist for this educator.'), code: Response::HTTP_NOT_FOUND);
            }

            if ($academicMajor->status === 'verified') {
                return $this->responseError(msg: __('Cannot reject an already verified academic major.'), code: Response::HTTP_CONFLICT);
            }

            $academicMajor->update([
                'status' => 'rejected',
                'approval_document' => false,
                'rejection_reason' => $request->input('rejection_reason'),
            ]);

            return $this->responseData(data: ['academic_major' => $academicMajor], msg: __('The academic major has been rejected successfully'));
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to reject academic major. Please try again.'), code: 500);
        }
    }

    public function approveIdentity(Request $request, Educator $educator): JsonResponse
    {
        try {
            if (!$educator->profile) {
                return $this->responseError(msg: __('Educator profile not found.'), code: Response::HTTP_NOT_FOUND);
            }

            if ($educator->profile->identity_status === 'verified') {
                return $this->responseError(msg: __('The identity is already verified.'), code: Response::HTTP_CONFLICT);
            }

            $educator->profile()->update([
                'identity_status' => 'verified',
                'rejection_reason' => null,
            ]);

            return $this->responseData(msg: __('The identity has been verified successfully'));
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to approve identity. Please try again.'), code: 500);
        }
    }

    public function rejectIdentity(Request $request, Educator $educator): JsonResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:65535',
        ]);

        try {
            if (!$educator->profile) {
                return $this->responseError(msg: __('Educator profile not found.'), code: Response::HTTP_NOT_FOUND);
            }

            $educator->profile()->update([
                'identity_status' => 'rejected',
                'rejection_reason' => $request->input('rejection_reason'),
            ]);

            return $this->responseData(msg: __('The identity has been rejected successfully'));
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to reject identity. Please try again.'), code: 500);
        }
    }

    public function suspend(Request $request, Educator $educator): JsonResponse
    {
        $request->validate([
            'suspension_reason' => 'nullable|string|max:65535',
            // 'suspension_days' => 'nullable|integer|min:1',
        ]);

        try {
            if ($educator->status->value === 'suspended') {
                return $this->responseError(msg: __('The educator is already suspended.'), code: Response::HTTP_CONFLICT);
            }

            $suspensionData = [
                'status' => 'suspended',
                'rejection_reason' => $request->input('suspension_reason'),
            ];

            // if ($request->has('suspension_days')) {
            //     $suspensionData['suspension_ends_at'] = now()->addDays($request->input('suspension_days'));
            // }

            $educator->update($suspensionData);
            return $this->responseData(data: ['educator' => new EducatorProfileResource($educator)],msg: __('The educator has been suspended successfully'));
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to suspend educator. Please try again.'), code: 500);
        }
    }

    public function unsuspend(Request $request, Educator $educator): JsonResponse
    {
        try {
            if ($educator->status->value !== 'suspended') {
                return $this->responseError(msg: __('The educator is not suspended.'), code: Response::HTTP_CONFLICT);
            }

            $educator->update([
                'status' => 'active',
                'suspension_reason' => null,
            ]);

            return $this->responseData(data: ['educator' => new EducatorProfileResource($educator)], msg: __('The educator has been unsuspended successfully'));
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to unsuspend educator. Please try again.'), code: 500);
        }
    }


    public function invite(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return $this->responseError(msg: __('Please provide a valid email address.'), code: 422);
        }

        $email = $request->input('email');

        try {
            $existingEducator = Educator::where('email', $email)->first();

            if ($existingEducator) {
                return $this->responseError(msg: __('An educator with this email already exists.'), code: 409);
            }

            $existingInvitation = EducatorInvitation::where('email', $email)->whereNull('completed_at')->where('expires_at', '>', now())->first();

            if ($existingInvitation) {
                return $this->responseError(msg: __('An invitation has already been sent to this email.'), code: 409);
            }

            EducatorInvitation::where('email', $email)->delete();

            $invitation = EducatorInvitation::create([
                'email' => $email,
                'token' => EducatorInvitation::generateToken(),
                'expires_at' => now()->addDays(7),
            ]);

            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
            $registrationUrl = rtrim($frontendUrl, '/') . '/educator/login?token=' . $invitation->token;

            try {
                Mail::to($email)->send(new InviteEducatorMail($registrationUrl));
            } catch (\Throwable $e) {
                Log::error('Failed to send invitation email', [
                    'email' => $email,
                    'registration_url' => $registrationUrl,
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $invitation->delete();

                return $this->responseError(
                    msg: __('Failed to send the invitation email. Please try again or contact support.'),
                    code: 500,
                    data: config('app.debug') ? [$e->getMessage()] : []
                );
            }

            return $this->responseData(code: 201, msg: __('Invitation sent successfully.'));
        } catch (\Throwable $e) {
            Log::error('Educator invite error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->responseError(msg: __('Failed to send invitation. Please try again.'), code: 500);
        }
    }

    public function completeRegistration(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->responseError(msg: $validator->errors()->first(), code: 422, data: $validator->errors()->toArray());
        }

        try {
            $invitation = EducatorInvitation::where('token', $request->input('token'))->first();

            if (!$invitation || !$invitation->isValid()) {
                return $this->responseError(msg: __('This invitation link is invalid or has expired.'), code: 400);
            }

            $existingEducator = Educator::where('email', $invitation->email)->first();
            if ($existingEducator) {
                $invitation->markAsCompleted();
                return $this->responseError(msg: __('This email is already registered.'), code: 409);
            }

            $educator = Educator::create([
                'name' => $request->input('name'),
                'email' => $invitation->email,
                'phone' => $request->input('phone'),
                'password' => bcrypt($request->input('password')),
                'status' => 'pending',
                'is_accepted' => false,
            ]);

            $invitation->markAsCompleted();

            return $this->responseData(data: ['educator' => $educator], code: 201, msg: __('Registration completed successfully.'));
        } catch (\Throwable $e) {
            Log::error('Educator complete registration error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->responseError(msg: __('Failed to complete registration. Please try again.'), code: 500);
        }
    }

    public function toggleFeatured(Request $request, Educator $educator): JsonResponse
    {
        try {
            $educator->update([
                'is_featured' => !$educator->is_featured,
            ]);

            return $this->responseData(data: ['educator' => new EducatorProfileResource($educator)], msg: __('The educator featured status has been updated successfully'));
        } catch (\Exception $e) {
            return $this->responseError(msg: __('Failed to update featured status. Please try again.'), code: 500);
        }
    }


}
