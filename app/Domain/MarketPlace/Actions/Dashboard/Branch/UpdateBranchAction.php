<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use App\Support\Services\ImagesServices\ImageService;

class UpdateBranchAction
{
    protected BranchRepository $branchRepository;


    public function __construct(BranchRepository $branchRepository, ImageService $imageService)
    {
        $this->branchRepository = $branchRepository;
        $this->imageService = $imageService;
    }


    public function handle(int $element_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        if (isset($data['cover_image']) && $data['cover_image']) {
            $file = $data['cover_image'];
            if ($data['cover_image'] != null && $file->disk) {
                $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['cover_image'] = $image['path'];
            } else {
                unset($data['cover_image']);
            }
        } else {
            unset($data['cover_image']);
        }
        if (isset($data['manager_id']) && $data['manager_id'] == "")
            $data['manager_id'] = null;


        $model = $this->branchRepository->update($element_id, $data);
        if (isset($data['package_type_services'])) {
            $package_type_services = collect($data['package_type_services'])->where('status', 'active');
            foreach ($package_type_services as $option) {
                $model->merchant_branch_type_services()
                    ->create([
                        'type_service_id' => $option['type_service_id'],
                        'package_id' => $option['package_id'],
                        'commission' => $option['commission'],
                        'discount_rate' => $option['discount_rate'],
                        'desc' => $option['desc'],
                        'status' => $option['status']
                    ]);
            }
        }
        return $model;
    }
}
