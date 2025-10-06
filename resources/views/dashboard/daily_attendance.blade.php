<div class="col-md-6">
    <div class="d_card" style="background: aliceblue;">
        <div class="row" style="display: flex;flex-direction: row;align-items: center;">
            <h4 class="col-md-6"> Daily Attendance</h4>
            <input class="col-md-3" type="date" onchange="get_data_count()" value="{{ date('Y-m-d') }}" name="date" id="date_first_card" style="border: 1px solid #009cf5;background: transparent;padding: 3px;border-radius: 7px;">
            <div class="col-md-3">
                <button onclick="daily_report()" type="button" class="btn btn-primary btn-sm text-white">Get Report <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="c_card" id="all-employees">
                    <h6 style="font-size:15px !important;">All Employees</h6>
                    <div class="col-md-12 card_flex">
                        <h3 class="count-all-employees col-md-6" id="count-all-employees">0</h3>
                        <i class="fa fa-user col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="c_card" id="present">
                    <h6 style="font-size:15px !important;">Present</h6>
                    <div class="col-md-12 card_flex">
                        <h3 class="count-present col-md-6" id="count-present">0</h3>
                        <i class="fa fa-laptop col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="c_card" id="absent">
                    <h6 style="font-size:15px !important;">Absent</h6>
                    <div class="col-md-12 card_flex">
                        <h3 class="count-absent col-md-6" id="count-absent">0</h3>
                        <i class="fa fa-home col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="c_card" id="late">
                    <h6 style="font-size:15px !important;">Late</h6>
                    <div class="col-md-12 card_flex" >
                        <h3 class="count-late col-md-6" id="count-late">0</h3>
                        <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        function get_data_count() {
            var loader = '<img src="{{ asset('assets/loader.gif') }}"  alt="loader" style="height: 24px;width: 24px;">';
            $('#count-all-employees').html(loader);
            $('#count-present').html(loader);
            $('#count-absent').html(loader);
            $('#count-late').html(loader);

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
                    $('#count-present').html(data.present_count);
                    $('#count-absent').html(data.absent_count);
                    $('#count-late').html(data.late_count);
                    $('#all_present_list').empty();
                    $('#all_employee_list').empty();
                    $('#all_absent_list').empty();
                    $('#all_late_list').empty();
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
