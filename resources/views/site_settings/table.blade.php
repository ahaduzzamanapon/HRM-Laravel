<div class="table-responsive">
    <table class="table data_t" id="siteSettings-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Name</th>
        <th>Logo</th>
        <th>Slogan</th>

                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($siteSettings as $siteSetting)
            <tr>
                <td>{{ $siteSetting->id }}</td>
            <td>{{ $siteSetting->name }}</td>
            <td><img src="{{asset($siteSetting->logo)}}" alt="" width="100"></td>
            <td>{{ $siteSetting->slogan }}</td>
          
                <td>
                    <div class='btn-group'>
                        <a href="{{ route('siteSettings.show', [$siteSetting->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('siteSettings.edit', [$siteSetting->id]) }}" class='btn btn-outline-primary btn-xs'><i
                                class="im im-icon-Pen"  data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
