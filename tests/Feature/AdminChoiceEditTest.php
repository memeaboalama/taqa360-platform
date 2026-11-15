<?php

namespace Tests\Feature;

use App\Domain\Admin\Actions\Dashboard\Menu\Choice\FetchSingleChoiceAction;
use App\Domain\Admin\Actions\Dashboard\Menu\Choice\UpdateChoiceAction;
use Domain\MarketPlace\Models\Choice;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class AdminChoiceEditTest extends TestCase
{
    public function test_choice_id_is_preserved_when_saving_an_edited_choice(): void
    {
        $merchantId = 12;
        $choiceId = 34;
        $choice = new Choice();
        $choice->setRawAttributes(['id' => $choiceId, 'status' => 'active']);
        $choice->setTranslations('title', ['en' => 'Choice', 'ar' => 'اختيار']);

        $fetchAction = Mockery::mock(FetchSingleChoiceAction::class);
        $fetchAction->shouldReceive('handle')->once()->with($choiceId, $merchantId)->andReturn($choice);
        $this->app->instance(FetchSingleChoiceAction::class, $fetchAction);

        $updateAction = Mockery::mock(UpdateChoiceAction::class);
        $updateAction->shouldReceive('handle')
            ->once()
            ->with($choiceId, Mockery::on(fn ($data) => $data['title'] === ['en' => 'Choice', 'ar' => 'اختيار'] && $data['status'] === 'active'));
        $this->app->instance(UpdateChoiceAction::class, $updateAction);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        Livewire::test(\Application\Admin\Livewire\Merchant\Tabs\Choices\Modal\Update::class, ['merchantId' => $merchantId])
            ->call('updateChoice', $choiceId)
            ->assertSet('choiceId', $choiceId)
            ->call('store');
    }
}
