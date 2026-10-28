<table class="table table-bordered align-middle mb-0 datatable">
                    <thead>
                    <tr>
                        <th>{{ __('admin::home.ID') }}</th>
                        <th>{{ __('admin::home.Title') }}</th>
                        <th>{{ __('admin::home.message') }}</th>
                        <th>{{ __('admin::home.Send Name') }}</th>
                        <th>{{ __('admin::home.Date') }}</th>
                        <th class="text-center">{{ __('admin::home.Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr wire:key>
                        @foreach($rows as $row)
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->title }}</td>
                        <td>{{ substr($row->body, 0, 50) }}</td>
                        <td>{{ $row->admin?->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->created_at)->format('j F Y') }}</td>
                        <td class="text-center">
                            <div class="hstack gap-2">
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-info-transparent rounded-pill" title="view" wire:click="$emit('showNotification',{{ $row->id }})"><i class="ri-eye-line"></i></a>
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    </tbody>
                    </table>
