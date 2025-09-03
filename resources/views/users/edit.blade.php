@extends('layouts.default')

@section('title')
User @parent
@stop

@section('content')
    <section class="content-header">
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3" style="border: 1px solid;padding: 7px;border-radius: 6px;">
                        <!-- Side Nav tabs -->
                        <div class="flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button style="width: 100%;" class="nav-link active" id="v-pills-employee-details-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-employee-details" type="button" role="tab"
                                aria-controls="v-pills-employee-details" aria-selected="true"><i
                                    class="im im-icon-User"></i> Employee Details</button>
                            <button style="width: 100%;" class="nav-link" id="v-pills-training-details-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-training-details" type="button" role="tab"
                                aria-controls="v-pills-training-details" aria-selected="false"><i
                                    class="im im-icon-Bookmark"></i> Training Details</button>
                            <button style="width: 100%;" class="nav-link" id="v-pills-job-experience-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-job-experience" type="button" role="tab"
                                aria-controls="v-pills-job-experience" aria-selected="false"><i
                                    class="im im-icon-Briefcase"></i> Job Experience</button>
                            <button style="width: 100%;" class="nav-link" id="v-pills-educational-qualifications-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-educational-qualifications" type="button"
                                role="tab" aria-controls="v-pills-educational-qualifications" aria-selected="false"><i
                                    class="im im-icon-Graduation-Cap"></i> Educational Qualifications</button>
                            <button style="width: 100%;" class="nav-link" id="v-pills-nominee-information-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-nominee-information" type="button" role="tab"
                                aria-controls="v-pills-nominee-information" aria-selected="false"><i
                                    class="im im-icon-Add-User"></i> Nominee Information</button>
                            <button style="width: 100%;" class="nav-link" id="v-pills-promotion-details-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-promotion-details" type="button" role="tab"
                                aria-controls="v-pills-promotion-details" aria-selected="false"><i
                                    class="im im-icon-Arrow-Up"></i> Promotion Details</button>
                            <button style="width: 100%;" class="nav-link" id="v-pills-salary-increment-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-salary-increment" type="button" role="tab"
                                aria-controls="v-pills-salary-increment" aria-selected="false"><i
                                    class="im im-icon-Money-2"></i> Salary Increment</button>
                            <button style="width: 100%;" class="nav-link" id="v-pills-transfer-details-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-transfer-details" type="button" role="tab"
                                aria-controls="v-pills-transfer-details" aria-selected="false"><i
                                    class="im im-icon-Shuffle"></i> Transfer Details</button>
                            <button style="width: 100%;" class="nav-link" id="v-pills-personal-documents-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-personal-documents" type="button" role="tab"
                                aria-controls="v-pills-personal-documents" aria-selected="false"><i
                                    class="im im-icon-Folder-WithDocument"></i> Personal Documents</button>
                            <button style="width: 100%;" class="nav-link" id="v-pills-salary-structure-tab"
                                data-bs-toggle="pill" data-bs-target="#v-pills-salary-structure" type="button" role="tab"
                                aria-controls="v-pills-salary-structure" aria-selected="false"><i
                                    class="im im-icon-Money-Bag"></i> Salary Structure</button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <!-- Tab Content -->
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-employee-details" role="tabpanel"
                                aria-labelledby="v-pills-employee-details-tab">
                                {!! Form::model($users, ['route' => ['users.update', $users->id], 'method' => 'patch', 'files' => true, 'class' => 'form-horizontal col-md-12']) !!}
                                <div class="row">
                                    @include('users.fields') {{-- This will contain the basic user fields --}}
                                </div>
                                <div class="form-group col-sm-12" style="text-align-last: right;">
                                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                    <a href="{{ route('users.index') }}" class="btn btn-primary">Cancel</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div class="tab-pane fade" id="v-pills-training-details" role="tabpanel"
                                aria-labelledby="v-pills-training-details-tab">
                                @include('users._training_details_form')
                            </div>
                            <div class="tab-pane fade" id="v-pills-job-experience" role="tabpanel"
                                aria-labelledby="v-pills-job-experience-tab">
                                @include('users._job_experience_form')
                            </div>
                            <div class="tab-pane fade" id="v-pills-educational-qualifications" role="tabpanel"
                                aria-labelledby="v-pills-educational-qualifications-tab">
                                @include('users._educational_qualifications_form')
                            </div>
                            <div class="tab-pane fade" id="v-pills-nominee-information" role="tabpanel"
                                aria-labelledby="v-pills-nominee-information-tab">
                                @include('users._nominee_information_form')
                            </div>
                            <div class="tab-pane fade" id="v-pills-promotion-details" role="tabpanel"
                                aria-labelledby="v-pills-promotion-details-tab">
                                @include('users._promotion_details_form')
                            </div>
                            <div class="tab-pane fade" id="v-pills-salary-increment" role="tabpanel"
                                aria-labelledby="v-pills-salary-increment-tab">
                                @include('users._salary_increment_form')
                            </div>
                            <div class="tab-pane fade" id="v-pills-transfer-details" role="tabpanel"
                                aria-labelledby="v-pills-transfer-details-tab">
                                @include('users._transfer_details_form')
                            </div>
                            <div class="tab-pane fade" id="v-pills-personal-documents" role="tabpanel"
                                aria-labelledby="v-pills-personal-documents-tab">
                                @include('users._personal_documents_form')
                            </div>
                            <div class="tab-pane fade" id="v-pills-salary-structure" role="tabpanel"
                                aria-labelledby="v-pills-salary-structure-tab">
                                @include('users._salary_structure_form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        $(document).ready(function () {
            // Function to save active tab to localStorage
            $("button[data-bs-toggle=\"pill\"]").on("shown.bs.tab", function (e) {
                localStorage.setItem("activeUserTab", $(e.target).attr("id"));
            });

            // Function to activate tab on page load
            const activeTab = localStorage.getItem("activeUserTab");
            if (activeTab) {
                $("#" + activeTab).tab("show");
            } else {
                // Default to Employee Details tab if no active tab is stored
                $("#v-pills-employee-details-tab").tab("show");
            }

            var d = new Date();
            var emp_id = $('#emp_id').val()
            if (emp_id == '') {
                $('#emp_id').val('EMP-' + d.getTime());
            }
        });
    </script>
@endsection