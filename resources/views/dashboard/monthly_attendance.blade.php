<div class="col-md-6">
    <div class="d_card" style="background: aliceblue;">
        <div class="row" style="display: flex;flex-direction: row;align-items: center;">
            <h4 class="col-md-6">Monthly</h4>
            <input class="col-md-4" type="month" onchange="get_monthly_data()" value="2025-10" id="date_monthly" style="border: 1px solid #009cf5;background: transparent;padding: 3px;border-radius: 7px;">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="c_card">
                    <div class="col-md-12 p-0 card_flex">
                        <h6 class="col-md-6 p-0" style="font-size: 15px !important;">Leave</h6>
                        <a href="javascript:void(0)" onclick="get_leave_monthly()" class="col-md-6 p-0" style="text-align: -webkit-center;cursor: pointer;;">Get Report
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-12 card_flex">
                        <h3 class="col-md-6" id="count_leave_monthly">0</h3>
                        <i class="fa fa-sign-out col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="c_card">
                    <div class="col-md-12 p-0 card_flex">
                        <h6 class="col-md-6 p-0" style="font-size: 15px !important;">Extra Present</h6>
                        <a href="javascript:void(0)" onclick="get_extra_present_monthly(event)" class="col-md-6 p-0" style="text-align: -webkit-center;cursor: pointer;;">Get Report
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-12 card_flex">
                        <h3 class="count-all-employees col-md-6" id="count_extra_present_monthly">0</h3>
                        <i class="fa fa-user-plus col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="c_card">
                    <div class="col-md-12 p-0 card_flex">
                        <h6 class="col-md-6 p-0" style="font-size: 15px !important;">Late</h6>
                        <a href="javascript:void(0)" onclick="get_late_monthly(event)" class="col-md-6 p-0" style="text-align: -webkit-center;cursor: pointer;;">Get Report
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-12 card_flex">
                        <h3 class=" col-md-6" id="count_late_monthly">0</h3>
                        <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="c_card">
                    <div class="col-md-12 p-0 card_flex">
                        <h6 class="col-md-6 p-0" style="font-size: 15px !important;">Meeting</h6>
                        <a href="javascript:void(0)" onclick="get_meeting_monthly(event)" class="col-md-6 p-0" style="text-align: -webkit-center;cursor: pointer;;">Get Report
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-12 card_flex">
                        <h3 class="col-md-6" id="count_meeting_monthly">0</h3>
                        <i class="fa fa-handshake-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
       // get_monthly_data();
    });
</script>
    <script>
        function get_monthly_data() {
            var loader = '<img src="{{ asset('assets/loader.gif') }}"  alt="loader" style="height: 24px;width: 24px;">';
            $('#count_leave_monthly').html(loader);
            $('#count_present_monthly').html(loader);
            $('#count_absent_monthly').html(loader);
            $('#count_late_monthly').html(loader);

            const date = $('#date_first_card').val();
            $.ajax({
                type: 'POST',
                url: '{{ route('attendance.daily-report') }}',
                data: {
                    date: date,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var data = response;
                    $('#count-all-employees').html(data.all_employees);
                    $('#count_present_monthly').html(data.present_count);
                    $('#count_absent_monthly').html(data.absent_count);
                    $('#count_late_monthly').html(data.late_count);
                }
            })
        }
    
        function daily_report()  {
            const date = $('#date_first_card').val();
            $.ajax({
                type: 'POST',
                url: '{{ route('attendance.daily-report') }}',
                data: {
                    date: date,
                    _token: '{{ csrf_token() }}',
                    type: 1
                },
                success: function(response) {
                    var popupWindow = window.open('', '_blank', 'width=1000,height=700,left=' + (screen.width/2 - 500) + ',top=' + (screen.height/2 - 350));
                    popupWindow.document.write(response); 
                    popupWindow.focus();
                },
                error: function(xhr, status, error) {
                    alert('Something went wrong: ' + error);
                }
            });

        }
    </script>
@endpush