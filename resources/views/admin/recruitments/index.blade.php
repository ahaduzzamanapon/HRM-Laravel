@extends('layouts.default')

@section('title')
Recruitment / Jobs @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Recruitments</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-primary" href="{{ route('recruitments.create') }}">
                    <i class="fa fa-plus"></i> Add New Job Post
                </a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @include('flash::message')

    <div class="clearfix"></div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center" id="recruitments-table">
                    <thead class="bg-light">
                        <tr>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($recruitments as $job)
                        <tr>
                            <td class="align-middle fw-bold">{{ $job->title }}</td>
                            <td class="align-middle">{{ $job->location ?? 'N/A' }}</td>
                            <td class="align-middle">
                                @if($job->status == 'published')
                                    <span class="badge badge-success px-3 py-2 rounded-pill">Published</span>
                                @elseif($job->status == 'draft')
                                    <span class="badge badge-secondary px-3 py-2 rounded-pill">Draft</span>
                                @else
                                    <span class="badge badge-danger px-3 py-2 rounded-pill">Closed</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $job->deadline ? $job->deadline->format('M d, Y') : 'No Deadline' }}</td>
                            <td class="align-middle">
                                <form action="{{ route('recruitments.destroy', $job->id) }}" method="POST">
                                    <div class='btn-group'>
                                        <a href="{{ route('recruitments.show', [$job->id]) }}" class='btn btn-outline-info btn-sm'>
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('recruitments.edit', [$job->id]) }}" class='btn btn-outline-primary btn-sm'>
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                No recruitment posts found. Click 'Add New Job Post' to get started.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $recruitments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
