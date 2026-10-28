<div>
    <div class="container">
        <h5 class="my-4 text-black"> @lang('marketplace::home.Please Select Employees')</h5>
        <form action="" class="row" wire:submit.prevent="store">
            @foreach($employees as $employee)
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ $employee->id }}"
                               wire:model="employees_ids"
                               {{ in_array($employee->id,$employees_ids) ? 'checked' : '' }}  id="checkebox-sm{{ $employee->id }}">
                        <label class="form-check-label text-black" style="cursor: pointer" for="checkebox-sm{{ $employee->id }}">
                            {{ $employee->name }} - {{ $employee->phone }}
                        </label>
                    </div>
                </div>
            @endforeach
            <div class="my-4">
                @error('employees_ids') <span class="text-danger d-inline-block">{{ $message }}</span>@enderror
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    @lang('marketplace::home.Save')
                                </span>
                    <span class="d-none" wire:loading.class.remove="d-none">
                                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                </button>
            </div>
        </form>
    </div>
</div>
