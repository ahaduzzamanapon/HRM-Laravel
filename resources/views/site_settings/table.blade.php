<div class="table-responsive">
    <table class="table" id="siteSettings-table">
        <thead>
            <tr>
                <th>Site Name</th>
                <th>Site Email</th>
                <th>Site Phone</th>
                <th>Site Address</th>
                <th>Site Logo</th>
                <th>Site Favicon</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($siteSettings as $siteSetting)
            <tr>
                <td>{{ $siteSetting->site_name }}</td>
                <td>{{ $siteSetting->site_email }}</td>
                <td>{{ $siteSetting->site_phone }}</td>
                <td>{{ $siteSetting->site_address }}</td>
                <td>
                    @if($siteSetting->site_logo)
                        <img src="{{ asset($siteSetting->site_logo) }}" alt="Site Logo" width="50">
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($siteSetting->site_favicon)
                        <img src="{{ asset($siteSetting->site_favicon) }}" alt="Site Favicon" width="20">
                    @else
                        N/A
                    @endif
                </td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('siteSettings.show', [$siteSetting->id]),
                        'editRoute' => route('siteSettings.edit', [$siteSetting->id]),
                        'deleteRoute' => route('siteSettings.destroy', [$siteSetting->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>