
    $(document).ready(function() {
        // Initialize DataTables with Buttons extension
        $('table.data-table').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            lengthChange: false,
            pageLength: 10,
            buttons: [
                {
                    extend: 'pageLength',
                    className: 'btn btn-sm btn-outline-primary mr-1',
                    text: '<i class="fas fa-list"></i> Page Length',
                    attr: {
                        title: 'Change Page Length'
                    }
                },
                {
                    extend: 'copy',
                    className: 'btn btn-sm btn-outline-primary mr-1',
                    text: '<i class="far fa-copy"></i> Copy',
                    attr: {
                        title: 'Copy to Clipboard'
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-sm btn-outline-primary mr-1',
                    text: '<i class="far fa-file-excel"></i> Excel',
                    attr: {
                        title: 'Export to Excel'
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm btn-outline-primary mr-1',
                    text: '<i class="far fa-file-pdf"></i> PDF',
                    attr: {
                        title: 'Export to PDF'
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm btn-outline-primary mr-1',
                    text: '<i class="fas fa-print"></i> Print',
                    attr: {
                        title: 'Print Table'
                    }
                }
            ],
            dom: 'Bfrtip',
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            language: {
                paginate: {
                    next: '<i class="fas fa-chevron-right"></i>',
                    previous: '<i class="fas fa-chevron-left"></i>'
                },
                search: '<i class="fas fa-search"></i>',
                searchPlaceholder: 'Search...',
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoFiltered: "(filtered from _MAX_ total entries)"
            }
        });

        // Add hover effect to buttons
        $('table.data-table').on('mouseenter', 'button.dt-button', function() {
            $(this).addClass('btn-outline-secondary').removeClass('btn-outline-primary');
        }).on('mouseleave', 'button.dt-button', function() {
            $(this).addClass('btn-outline-primary').removeClass('btn-outline-secondary');
        });
    });

