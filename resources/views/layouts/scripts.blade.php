<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
<script src="{{ asset('assets/js/core/libs.min.js') }}"></script>

<!-- External Library Bundle Script -->
<script src="{{ asset('assets/js/core/external.min.js') }}"></script>

<!-- Widgetchart Script -->
<script src="{{ asset('assets/js/charts/widgetcharts.js') }}"></script>

<!-- mapchart Script -->
<script src="{{ asset('assets/js/charts/vectore-chart.js') }}"></script>
<script src="{{ asset('assets/js/charts/dashboard.js') }}"></script>

<!-- fslightbox Script -->
<script src="{{ asset('assets/js/plugins/fslightbox.js') }}"></script>

<!-- Settings Script -->
<script src="{{ asset('assets/js/plugins/setting.js') }}"></script>

<!-- Slider-tab Script -->
<script src="{{ asset('assets/js/plugins/slider-tabs.js') }}"></script>

<!-- Form Wizard Script -->
<script src="{{ asset('assets/js/plugins/form-wizard.js') }}"></script>


<!-- App Script -->
<script src="{{ asset('assets/js/hope-ui.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {

        $('.table-responsive').on('show.bs.dropdown', function () {
            $('.btn-group').css('position', 'static');
        });

        $('.table-responsive').on('hide.bs.dropdown', function () {
            $('.btn-group').css('position', 'relative');
        });

        @if(Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ Session::get('success') }}',
            });
        @endif

        @if(Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ Session::get('error') }}',
            });
        @endif
    });
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function () {
        $('.table_data').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        });
    });
</script>
<script>

    $(document).ready(function () {
        $('#loader_div').hide();
    });
    $(document).on('ajaxStart', function () {
        $('#loader_div').show();
    }).on('ajaxStop', function () {
        $('#loader_div').hide();
    });
    $(window).on('beforeunload', function () {
        $('#loader_div').show();
    });
    $(window).on('pageshow', function (event) {
        if (event.originalEvent.persisted) {
            $('#loader_div').hide();
        }
    });
</script>