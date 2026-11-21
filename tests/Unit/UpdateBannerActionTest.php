<?php

namespace Tests\Unit;

use App\Domain\AppBase\Actions\AdBanner\UpdateBannerAction;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Support\Services\ImagesServices\ImageService;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class UpdateBannerActionTest extends TestCase
{
    public function test_missing_banner_fails_explicitly(): void
    {
        $repository = Mockery::mock(AdBannerRepository::class);
        $repository->shouldReceive('findByCols')
            ->once()
            ->with(['id' => 123])
            ->andReturnNull();

        $action = new UpdateBannerAction(
            $repository,
            Mockery::mock(ImageService::class)
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Ad banner not found.');

        $action->handle(123, []);
    }
}
