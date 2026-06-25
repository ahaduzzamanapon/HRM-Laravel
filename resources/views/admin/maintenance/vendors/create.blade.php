@extends('layouts.default')

@section('title')
Add Vendor @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add New Vendor</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-secondary" href="{{ route('admin.maintenance.vendors.index') }}">
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
            <form action="{{ route('admin.maintenance.vendors.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="contact_person">Contact Person</label>
                        <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ old('contact_person') }}">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer px-0 bg-transparent text-right">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Vendor</button>
                    <a href="{{ route('admin.maintenance.vendors.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
