@extends('layouts.default')

@section('title')
Edit Maintenance Type @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Maintenance Type</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-secondary" href="{{ route('admin.maintenance.types.index') }}">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.maintenance.types.update', $type->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $type->name) }}">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" {{ old('status', $type->status) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $type->status) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $type->description) }}</textarea>
                    </div>
                </div>

                <div class="card-footer px-0 bg-transparent text-right">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Type</button>
                    <a href="{{ route('admin.maintenance.types.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
