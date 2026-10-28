<?php

namespace App\Domain\AppBase\Actions\AdBanner;

use App\Domain\AppBase\Repositories\AdBannerRepository;
use Illuminate\Support\Facades\Storage;

class DeleteBannerAction
{
    public function __construct(
        protected AdBannerRepository $bannerRepository
    ) {}

    public function handle(int $id): bool
    {
        $banner = $this->bannerRepository->findByCols(['id' => $id]);

        if (!$banner) {
            return false;
        }

        $this->deleteFiles($banner->content);

        return $this->bannerRepository->destroy($id);
    }

    protected function deleteFiles(array $content): void
    {
        if (isset($content['images'])) {
            foreach ($content['images'] as $imagePath) {
                Storage::disk('fileDisk')->delete($imagePath);
            }
        }

        if (isset($content['video']['file'])) {
            Storage::disk('fileDisk')->delete($content['video']['file']);
        }

        if (isset($content['video']['poster'])) {
            Storage::disk('fileDisk')->delete($content['video']['poster']);
        }
    }
}
