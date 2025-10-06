        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-6">Payroll Info</h4>
                    {{-- <input class="col-md-4" type="month" onchange="get_monthly_data()" value="2025-10" id="date_monthly" style="border: 1px solid #009cf5;background: transparent;padding: 3px;border-radius: 7px;"> --}}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total Salary Grade</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_leave_monthly">{{ $totalSalaryGrades }}</h3>
                                <i class="fa fa-sign-out col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('salaryGrades.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total Tax Setups</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_extra_present_monthly">{{ $totalTaxSetups }}</h3>
                                <i class="fa fa-user-plus col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('taxSetups.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total Loans</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_late_monthly">{{ $totalLoans }}</h3>
                                <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Pending Loans</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_meeting_monthly">{{ $pendingLoans }}</h3>
                                <i class="fa fa-handshake-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>