<?php

namespace App\Application\Educator\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Educator\Models\Educator;
use Domain\Educator\Actions\Profile\UpdateBasicInfoAction;
use Domain\Educator\Actions\Profile\UpdateBiographyAction;
use Domain\Educator\Actions\Profile\UpdateAcademicMajorAction;
use Domain\Educator\Actions\Profile\UpdateAchievementsAction;
use Domain\Educator\Actions\Profile\UpdateLanguagesAction;
use Domain\Educator\Actions\Profile\UpdateLanguagesBulkAction;
use Domain\Educator\Actions\Profile\UpdateSkillsAction;
use Domain\Educator\Actions\Profile\UpdateServicesAction;
use App\Domain\Educator\Resource\EducatorResource;
use App\Application\Educator\Requests\Profile\BasicInfoRequest;
use App\Application\Educator\Requests\Profile\BiographyRequest;
use App\Application\Educator\Requests\Profile\AcademicMajorRequest;
use App\Application\Educator\Requests\Profile\AchievementRequest;
use App\Application\Educator\Requests\Profile\LanguageRequest;
use App\Application\Educator\Requests\Profile\LanguagesBulkRequest;
use App\Application\Educator\Requests\Profile\PaymentInfoRequest;
use App\Application\Educator\Requests\Profile\SkillsUpdateRequest;
use App\Application\Educator\Requests\Profile\ServicesUpdateRequest;
use App\Application\Educator\Requests\Profile\ServiceRequest;
use App\Application\Educator\Requests\Profile\SkillAddRequest;
use App\Application\Educator\Requests\Profile\SkillCustomRequest;
use App\Application\Educator\Requests\Profile\SkillLevelUpdateRequest;
use App\Application\Educator\Requests\Profile\SocialLinksRequest;
use App\Application\Educator\Requests\UploadFileRequest;
use App\Domain\AppBase\Resources\LanguageResource;
use App\Domain\Educator\Resource\Profile\AcademicMajorResource;
use App\Domain\Educator\Resource\Profile\AchievementResource;
use App\Domain\Educator\Resource\Profile\EducatorProfileResource;
use App\Domain\Educator\Resource\Profile\EducatorServiceResource;
use App\Domain\Educator\Resource\Profile\EducatorSkillResource;
// use App\Http\Resources\EducatorServiceResource;
// use App\Http\Resources\EducatorSkillResource;
use App\Support\Services\ImagesServices\ImageService;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\AppBase\Models\Edu\Service as BaseService;
use Domain\AppBase\Models\Edu\Skill as BaseSkill;
use Domain\AppBase\Models\Edu\AcademicMajor as BaseAcademicMajor;
use Domain\Educator\Models\EducatorAcademicMajor;
use Illuminate\Support\Facades\Log;

class EducatorProfileController extends Controller
{
    use ApiResponseTraitV2;

    protected UpdateBasicInfoAction $basicInfoAction;
    protected UpdateBiographyAction $biographyAction;
    protected UpdateAcademicMajorAction $academicMajorAction;
    protected UpdateAchievementsAction $achievementsAction;
    protected UpdateLanguagesAction $languagesAction;
    protected ImageService $imageService;

    public function __construct(
        UpdateBasicInfoAction $basicInfoAction,
        UpdateBiographyAction $biographyAction,
        UpdateAcademicMajorAction $academicMajorAction,
        UpdateAchievementsAction $achievementsAction,
        UpdateLanguagesAction $languagesAction,
        ImageService $imageService
    ) {
        $this->basicInfoAction = $basicInfoAction;
        $this->biographyAction = $biographyAction;
        $this->academicMajorAction = $academicMajorAction;
        $this->achievementsAction = $achievementsAction;
        $this->languagesAction = $languagesAction;
        $this->imageService = $imageService;
    }

    public function show(Request $request)
    {
        $educator = $request->user();
        return $this->responseData(['educator' => new EducatorProfileResource($educator->load(['profile','files','academicMajors','achievements','languages','services','skills','socialLinks', 'bankInfo']))]);
    }

    public function updateBasicInfo(BasicInfoRequest $request)
    {
        $educator = $request->user();
        $front = $request->file('identityImageFront');
        $back = $request->file('identityImageBack');
        $updated = $this->basicInfoAction->handle($educator, $request->validated(), $front, $back);
        return $this->responseData(['educator' => new EducatorProfileResource($updated)]);
    }

    public function updateBiography(BiographyRequest $request)
    {
        Log::info('EducatorProfileController updateBiography called', ['request' => $request->all()]);
        $educator = $request->user();
        $profileImage = $request->file('profileImage');
        $bioData = $request->validated()['bio'] ?? [];
        $social = $request->validated()['social_links'] ?? [];
        $this->biographyAction->handle($educator, $bioData, $social, $profileImage);
        return $this->responseData(['educator' => new EducatorProfileResource($educator->fresh()->load(['profile','files','socialLinks','bios']))]);
    }

    public function languagesIndex(Request $request)
    {
        $educator = $request->user();
        $items = $educator->languages()->with('language')->get();
        return $this->responseData(['items' => LanguageResource::collection($items)]);
    }

    public function languagesUpdate(LanguagesBulkRequest $request, UpdateLanguagesBulkAction $action)
    {
        $educator = $request->user();
        $action->handle($educator, $request->validated()['languages']);
        $items = $educator->fresh()->languages()->with('language')->get();
        return $this->responseData(['items' => LanguageResource::collection($items)]);
    }

    public function saveLanguage(LanguageRequest $request)
    {
        $educator = $request->user();
        $editingId = $request->input('id');
        $this->languagesAction->handle($educator, $request->validated(), $editingId);
        $items = $educator->fresh()->languages()->with('language')->get();
        return $this->responseData(['items' => LanguageResource::collection($items)]);
    }

    public function deleteLanguage(Request $request, $id)
    {
        $educator = $request->user();
        $this->languagesAction->delete($educator, (int)$id);
        $items = $educator->fresh()->languages()->with('language')->get();
        return $this->responseData(['items' => LanguageResource::collection($items)]);
    }

    public function skillsIndex(Request $request)
    {
        $educator = $request->user();
        $items = $educator->skills()->with('approvedSkill')->get();
        return $this->responseData(['items' => EducatorSkillResource::collection($items)]);
    }

    public function skillsUpdate(SkillsUpdateRequest $request, UpdateSkillsAction $action)
    {
        $educator = $request->user();
        $action->handle($educator, $request->validated()['skills']);
        $items = $educator->fresh()->skills()->with('approvedSkill')->get();
        return $this->responseData(['items' => EducatorSkillResource::collection($items)]);
    }

    public function servicesIndex(Request $request)
    {
        $educator = $request->user();
        $items = $educator->services()->with('approvedService')->get();
        return $this->responseData(['items' => EducatorServiceResource::collection($items)]);
    }

    public function servicesUpdate(ServicesUpdateRequest $request, UpdateServicesAction $action)
    {
        $educator = $request->user();
        $action->handle($educator, $request->validated()['services']);
        $items = $educator->fresh()->services()->with('approvedService')->get();
        return $this->responseData(['items' => EducatorServiceResource::collection($items)]);
    }

    public function socialLinksIndex(Request $request)
    {
        $educator = $request->user();
        $items = $educator->socialLinks()->get(['platform','url','id']);
        return $this->responseData(['items' => $items]);
    }

    public function updateSocialLinks(SocialLinksRequest $request)
    {
        $educator = $request->user();
        $links = $request->validated()['social_links'] ?? [];
        $educator->socialLinks()->delete();
        $valid = array_filter($links, fn($l) => !empty($l['platform']) && !empty($l['url']));
        if (!empty($valid)) {
            $educator->socialLinks()->createMany($valid);
        }
        $items = $educator->fresh()->socialLinks()->get(['platform','url','id']);
        return $this->responseData(['items' => $items]);
    }

    public function paymentInfoIndex(Request $request)
    {
        $educator = $request->user();
        $info = $educator->bankInfo;
        return $this->responseData(['paymentInfo' => $info]);
    }

    public function updatePaymentInfo(PaymentInfoRequest $request)
    {
        $educator = $request->user();
        $data = $request->validated();
        $educator->bankInfo()->updateOrCreate(['id' => $educator->bank_id ?? null], [
            'bank_id' => $data['bank_id'] ?? null,
            'bank_name' => $data['bank_name'] ?? ($educator->bankInfo?->name ?? null),
            'name_on_card' => $data['name_on_card'] ?? null,
            'card_number' => $data['card_number'] ?? null,
            'cvv' => $data['cvv'] ?? null,
            'expire_month' => $data['expire_month'] ?? null,
            'expire_year' => $data['expire_year'] ?? null,
            'educator_id' => $educator->id,
        ]);
        $info = $educator->fresh()->bankInfo;
        return $this->responseData(['paymentInfo' => $info]);
    }

    public function uploadAvatar(UploadFileRequest $request)
    {
        $educator = $request->user();
        $file = $request->file('file');
        $this->biographyAction->handle($educator, [], [], $file);
        return $this->responseData(['files' => $educator->fresh()->files]);
    }

    public function uploadDocument(UploadFileRequest $request, $collection)
    {
        $allowed = ['identity_front','identity_back','passport_image','academic_certificate','profile_image'];
        if (!in_array($collection, $allowed)) {
            return $this->responseError([], 400, 'Collection not allowed');
        }
        $educator = $request->user();
        $file = $request->file('file');
        $path = "educators/documents";
        $disk = 'fileDisk';
        $oldFile = $educator->files()->where('collection_name', $collection)->first();
        $fileDetails = $this->imageService->storeImage($file, $path, \App\Support\Enum\FileTypesEnum::IDENTITY, $disk, 'webp', $oldFile?->path);
        $fileData = array_merge(['collection_name' => $collection], $fileDetails);
        $educator->files()->updateOrCreate(['collection_name' => $collection], $fileData);
        return $this->responseData(['files' => $educator->fresh()->files]);
    }

    public function deleteDocument(Request $request, $collection)
    {
        $allowed = ['identity_front','identity_back','passport_image','academic_certificate','profile_image'];
        if (!in_array($collection, $allowed)) {
            return $this->responseError([], 400, 'Collection not allowed');
        }
        $educator = $request->user();
        $file = $educator->files()->where('collection_name', $collection)->first();
        if ($file) {
            $this->imageService->destroyImg($file->path, $file->storage);
            $file->delete();
        }
        return $this->responseData(['files' => $educator->fresh()->files]);
    }

    public function workExperienceIndex(Request $request)
    {
        $educator = $request->user();
        $items = $educator->achievements()->where('type','work_experience')->with('file')->orderBy('start_date','desc')->get();
        return $this->responseData(['items' => AchievementResource::collection($items)]);
    }

    public function workExperienceStore(AchievementRequest $request)
    {
        $educator = $request->user();
        $data = array_merge($request->validated(), ['type' => 'work_experience']);
        $this->achievementsAction->handle($educator, $data, null, $request->file('attachment'));
        $items = $educator->fresh()->achievements()->where('type','work_experience')->with('file')->orderBy('start_date','desc')->get();
        return $this->responseData(['items' => AchievementResource::collection($items)]);
    }

    public function workExperienceUpdate(AchievementRequest $request, $id)
    {
        $educator = $request->user();
        $data = array_merge($request->validated(), ['type' => 'work_experience']);
        $this->achievementsAction->handle($educator, $data, (int)$id, $request->file('attachment'));
        $items = $educator->fresh()->achievements()->where('type','work_experience')->with('file')->orderBy('start_date','desc')->get();
        return $this->responseData(['items' => AchievementResource::collection($items)]);
    }

    public function workExperienceDestroy(Request $request, $id)
    {
        $educator = $request->user();
        $this->achievementsAction->delete($educator, (int)$id);
        $items = $educator->fresh()->achievements()->where('type','work_experience')->with('file')->orderBy('start_date','desc')->get();
        return $this->responseData(['items' => AchievementResource::collection($items)]);
    }

    public function certificationsIndex(Request $request)
    {
        $educator = $request->user();
        $items = $educator->achievements()->whereIn('type',['certificate','course','workshop'])->with('file')->orderBy('start_date','desc')->get();
        return $this->responseData(['items' => AchievementResource::collection($items)]);
    }

    public function certificationsStore(AchievementRequest $request)
    {
        $educator = $request->user();
        $data = $request->validated();
        if (empty($data['type'])) {
            $data['type'] = 'certificate';
        }
        $this->achievementsAction->handle($educator, $data, null, $request->file('attachment'));
        $items = $educator->fresh()->achievements()->whereIn('type',['certificate','course','workshop'])->with('file')->orderBy('start_date','desc')->get();
        return $this->responseData(['items' => AchievementResource::collection($items)]);
    }

    public function certificationsUpdate(AchievementRequest $request, $id)
    {
        $educator = $request->user();
        $data = $request->validated();
        if (empty($data['type'])) {
            $data['type'] = 'certificate';
        }
        $this->achievementsAction->handle($educator, $data, (int)$id, $request->file('attachment'));
        $items = $educator->fresh()->achievements()->whereIn('type',['certificate','course','workshop'])->with('file')->orderBy('start_date','desc')->get();
        return $this->responseData(['items' => AchievementResource::collection($items)]);
    }

    public function certificationsDestroy(Request $request, $id)
    {
        $educator = $request->user();
        $this->achievementsAction->delete($educator, (int)$id);
        $items = $educator->fresh()->achievements()->whereIn('type',['certificate','course','workshop'])->with('file')->orderBy('start_date','desc')->get();
        return $this->responseData(['items' => AchievementResource::collection($items)]);
    }

    public function servicesOptions(Request $request)
    {
        $services = BaseService::where('status', 'active')->get(['id','name']);
        $data = $services->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values();
        return $this->responseData(['items' => $data]);
    }

    public function serviceSave(ServiceRequest $request)
    {
        $educator = $request->user();
        $payload = $request->validated();
        $isCustom = (bool)($payload['is_custom'] ?? false);
        $data = [
            'service_id' => $isCustom ? null : ($payload['service_id'] ?? null),
            'custom_name' => $isCustom ? ($payload['custom_name'] ?? null) : null,
            'custom_description' => $payload['custom_description'] ?? null,
            'is_custom' => $isCustom,
        ];
        $id = $payload['id'] ?? null;
        $educator->services()->updateOrCreate(['id' => $id], $data);
        $items = $educator->fresh()->services()->with('approvedService')->get();
        return $this->responseData(['items' => EducatorServiceResource::collection($items)]);
    }

    public function serviceDelete(Request $request, $id)
    {
        $educator = $request->user();
        $educator->services()->find($id)?->delete();
        $items = $educator->fresh()->services()->with('approvedService')->get();
        return $this->responseData(['items' => EducatorServiceResource::collection($items)]);
    }

    public function skillSearch(Request $request)
    {
        $educator = $request->user();
        $q = (string)$request->query('q', '');
        $added = $educator->skills()->pluck('skill_id')->filter()->toArray();
        if (mb_strlen($q) < 2) {
            $results = BaseSkill::where('status','active')->whereNotIn('id',$added)->latest()->take(7)->get(['id','name']);
        } else {
            $results = BaseSkill::where('status','active')->whereNotIn('id',$added)->where('name','like','%'.$q.'%')->take(7)->get(['id','name']);
        }
        $data = $results->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values();
        return $this->responseData(['items' => $data]);
    }

    public function skillAdd(SkillAddRequest $request)
    {
        $educator = $request->user();
        $educator->skills()->create(['skill_id' => $request->validated()['skill_id'], 'is_custom' => false, 'level' => 3]);
        $items = $educator->fresh()->skills()->with('approvedSkill')->get();
        return $this->responseData(['items' => EducatorSkillResource::collection($items)]);
    }

    public function skillAddCustom(SkillCustomRequest $request)
    {
        $educator = $request->user();
        $name = $request->validated()['name'];
        $exists = $educator->skills()->where('is_custom', true)->where('custom_name', $name)->exists();
        if ($exists) {
            return $this->responseError([], 422, 'Skill already exists');
        }
        $educator->skills()->create(['is_custom' => true, 'custom_name' => $name, 'level' => 3]);
        $items = $educator->fresh()->skills()->with('approvedSkill')->get();
        return $this->responseData(['items' => EducatorSkillResource::collection($items)]);
    }

    public function skillUpdateLevel(SkillLevelUpdateRequest $request, $id)
    {
        $educator = $request->user();
        $model = $educator->skills()->findOrFail($id);
        $model->update(['level' => $request->validated()['level']]);
        $items = $educator->fresh()->skills()->with('approvedSkill')->get();
        return $this->responseData(['items' => EducatorSkillResource::collection($items)]);
    }

    public function skillDelete(Request $request, $id)
    {
        $educator = $request->user();
        $educator->skills()->find($id)?->delete();
        $items = $educator->fresh()->skills()->with('approvedSkill')->get();
        return $this->responseData(['items' => EducatorSkillResource::collection($items)]);
    }

    public function academicOptions(Request $request)
    {
        $majors = BaseAcademicMajor::where('status','active')->get(['id','name']);
        $data = $majors->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values();
        return $this->responseData(['approvedMajors' => $data]);
    }

    public function removeAcademicCertificateImage(Request $request, $id)
    {
        $educator = $request->user();
        $major = $educator->academicMajors()->find($id);
        if ($major && $major->certificateFile) {
            $this->imageService->destroyImg($major->certificateFile->path, $major->certificateFile->storage);
            $major->certificateFile->delete();
        }
        $items = $educator->fresh()->academicMajors()->with(['certificateFile','approvedMajor'])->get();
        return $this->responseData(['items' => AcademicMajorResource::collection($items)]);
    }

    public function removeAchievementImage(Request $request, $id)
    {
        $educator = $request->user();
        $achievement = $educator->achievements()->find($id);
        if ($achievement && $achievement->file) {
            $this->imageService->destroyImg($achievement->file->path, $achievement->file->storage);
            $achievement->file->delete();
        }
        $items = $educator->fresh()->achievements()->with('file')->get();
        return $this->responseData(['items' => AchievementResource::collection($items)]);
    }
}
