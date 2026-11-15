<div class="card custom-card">
    <div class="card-header">
        <h5 class="card-title mb-0">إعدادات التحصيل والتسويات</h5>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="save">
            <div class="mb-4">
                <label class="form-label">جهة استلام الأموال</label>
                <select class="form-select" wire:model.defer="recipient">
                    <option value="platform">المنصة</option>
                    <option value="merchant">التاجر</option>
                </select>
                @error('recipient') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <h6>وسائل التوريد</h6>
                <p class="text-muted">يُسمح بالتحصيل الكامل فقط. لكل طلب تُحفظ نسخة من إعدادات جهة الاستلام الحالية.</p>
                @foreach(['cash' => 'نقد', 'transfer' => 'تحويل', 'deposit' => 'إيداع'] as $method => $label)
                    <div class="border rounded p-3 mb-3">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="{{ $method }}"
                                   id="method-{{ $method }}" wire:model.defer="enabledMethods">
                            <label class="form-check-label fw-semibold" for="method-{{ $method }}">{{ $label }}</label>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">جهة الاستلام لهذه الوسيلة</label>
                                <input class="form-control" wire:model.defer="methods.{{ $method }}.recipient_name">
                                @error("methods.{$method}.recipient_name") <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            @if(in_array($method, ['transfer', 'deposit'], true))
                                <div class="col-md-6">
                                    <label class="form-label">بيانات الحساب المعتمد</label>
                                    <textarea class="form-control" rows="2" wire:model.defer="methods.{{ $method }}.account_data"></textarea>
                                    @error("methods.{$method}.account_data") <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            <div class="col-12">
                                <label class="form-label">تعليمات التوريد</label>
                                <textarea class="form-control" rows="2" wire:model.defer="methods.{{ $method }}.instructions"></textarea>
                                @error("methods.{$method}.instructions") <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
                @error('enabledMethods') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <h6>صلاحيات التسوية</h6>
                <div class="row g-3">
                    @foreach([
                        'reviewRoleIds' => 'مراجعة التسوية',
                        'approvalRoleIds' => 'اعتماد التوريد',
                        'correctionRoleIds' => 'التصحيح المالي',
                    ] as $property => $label)
                        <div class="col-lg-4">
                            <label class="form-label">{{ $label }}</label>
                            <select class="form-select" multiple wire:model.defer="{{ $property }}">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error($property . '.*') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            @if($this->canEditSettings())
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">حفظ الإعدادات</span>
                        <span wire:loading wire:target="save">جارٍ الحفظ...</span>
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
