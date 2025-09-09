@extends('layouts.default')

@section('title')
User @parent
@stop

@section('content')
<section class="content-header">
    <h1>User Profile</h1>
</section>

<div class="content">
    <div class="row">
        <div class="col-md-3">
            @include('users._profile_card')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#training_details" data-toggle="tab">Training Details</a></li>
                            <li><a href="#job_experience" data-toggle="tab">Job Experience</a></li>
                            <li><a href="#educational_qualifications" data-toggle="tab">Educational Qualifications</a></li>
                            <li><a href="#nominee_information" data-toggle="tab">Nominee Information</a></li>
                            <li><a href="#promotion_details" data-toggle="tab">Promotion Details</a></li>
                            <li><a href="#salary_increment" data-toggle="tab">Salary Increment</a></li>
                            <li><a href="#transfer_details" data-toggle="tab">Transfer Details</a></li>
                            <li><a href="#personal_documents" data-toggle="tab">Personal Documents</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="training_details">
                                @include('users._training_details_show')
                            </div>
                            <div class="tab-pane" id="job_experience">
                                @include('users._job_experience_show')
                            </div>
                            <div class="tab-pane" id="educational_qualifications">
                                @include('users._educational_qualifications_show')
                            </div>
                            <div class="tab-pane" id="nominee_information">
                                @include('users._nominee_information_show')
                            </div>
                            <div class="tab-pane" id="promotion_details">
                                @include('users._promotion_details_show')
                            </div>
                            <div class="tab-pane" id="salary_increment">
                                @include('users._salary_increment_show')
                            </div>
                            <div class="tab-pane" id="transfer_details">
                                @include('users._transfer_details_show')
                            </div>
                            <div class="tab-pane" id="personal_documents">
                                @include('users._personal_documents_show')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
