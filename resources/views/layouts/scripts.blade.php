<script src="{{ asset('assets/js/core/libs.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

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
            columnDefs: [
                { orderable: false, targets: -1 }
            ],
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
    var loaderSafetyTimer = null;

    function forceHideLoader() {
        if (loaderSafetyTimer) {
            clearTimeout(loaderSafetyTimer);
            loaderSafetyTimer = null;
        }
        $('#loader_div').stop(true, true).hide();
    }

    function showLoaderSafely(maxDurationMs) {
        $('#loader_div').show();
        if (loaderSafetyTimer) clearTimeout(loaderSafetyTimer);
        loaderSafetyTimer = setTimeout(function () {
            forceHideLoader();
        }, maxDurationMs || 2000);
    }

    // Override native alert to ALWAYS hide loader first and display non-blocking SweetAlert2
    window.alert = function (msg) {
        forceHideLoader();
        if (typeof Swal !== 'undefined') {
            var strMsg = msg ? msg.toString() : '';
            var isErr = strMsg && (strMsg.toLowerCase().indexOf('error') !== -1 || strMsg.toLowerCase().indexOf('failed') !== -1);
            Swal.fire({
                icon: isErr ? 'error' : 'success',
                title: isErr ? 'Notice' : 'Success',
                text: strMsg,
                confirmButtonColor: '#0177bc'
            });
        }
    };

    $(document).ready(function () {
        forceHideLoader();
    });

    $(document).on('ajaxStart', function () {
        showLoaderSafely(2500);
    }).on('ajaxStop ajaxComplete ajaxError', function () {
        forceHideLoader();
    });

    $(window).on('beforeunload', function () {
        if (!window.isDownloading) {
            showLoaderSafely(1500);
        }
        window.isDownloading = false;
    });

    $(window).on('pageshow focus blur', function () {
        setTimeout(forceHideLoader, 100);
    });

    // Prevent loader on download/export clicks
    $(document).on('click', 'a, button', function () {
        var href = $(this).attr('href') || '';
        var isDownload = $(this).attr('download') !== undefined || 
                         href.indexOf('payslip') !== -1 || 
                         href.indexOf('export') !== -1 ||
                         $(this).hasClass('no-loader');
        
        if (isDownload) {
            window.isDownloading = true;
            setTimeout(function () {
                forceHideLoader();
            }, 500);
        }
    });
</script>

<!-- Select2 JS & Automatic System-Wide Initialization -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function initGlobalSelect2(context) {
        if (typeof $.fn.select2 === 'undefined') return;
        
        var $scope = context ? $(context) : $(document);
        $scope.find('select').each(function() {
            var $select = $(this);
            
            // Exclude selects marked with .no-select2, DataTables controls, or calendar/picker dropdowns
            if ($select.hasClass('no-select2') || 
                $select.hasClass('dt-input') || 
                $select.parents('.dataTables_length').length > 0 ||
                $select.hasClass('swal2-select')) {
                return;
            }

            if (!$select.hasClass('select2-hidden-accessible')) {
                var parentModal = $select.closest('.modal');
                $select.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: $select.attr('placeholder') || 'Select an option',
                    allowClear: true,
                    dropdownParent: parentModal.length ? parentModal : $(document.body)
                });
            }
        });
    }

    $(document).ready(function() {
        initGlobalSelect2();
    });

    // Re-initialize Select2 when Bootstrap modals are opened
    $(document).on('shown.bs.modal', function(e) {
        initGlobalSelect2(e.target);
    });

    // Re-initialize Select2 when tab panels are displayed
    $(document).on('shown.bs.tab', function(e) {
        initGlobalSelect2(e.target);
    });
</script>
