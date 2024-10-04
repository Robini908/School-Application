<div>
    <table id="{{ $id }}" class="table table-bordered table-striped">
        <thead class="thead-light">
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>

    @push('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#{{ $id }}').DataTable({
                dom: 'Bfrtip',
                paging: true,
                searching: true,
                ordering: true,
                lengthChange: true,
                pageLength: 10,
                processing: true,
                serverSide: false,
                responsive: true,
                lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
                buttons: [
                    {
                        extend: 'csv',
                        text: 'Export CSV',
                        exportOptions: {
                            columns: ':visible:not(:last-child)',
                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            }
                        },
                        title: '{{ $title }}',
                        messageTop: '{{ $message }}'
                    },
                    {
                        extend: 'excel',
                        text: 'Export Excel',
                        exportOptions: {
                            columns: ':visible:not(:last-child)',
                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            }
                        },
                        title: '{{ $title }}',
                        messageTop: '{{ $message }}'
                    },
                    {
                        extend: 'pdf',
                        text: 'Export PDF',
                        exportOptions: {
                            columns: ':visible:not(:last-child)',
                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            }
                        },
                        title: '{{ $title }}',
                        messageTop: '{{ $message }}',
                        customize: function (doc) {
                            doc.content.unshift({
                                text: '{{ $title }}',
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            });
                            doc.footer = function (currentPage, pageCount) {
                                return {
                                    text: 'Page ' + currentPage + ' of ' + pageCount,
                                    alignment: 'center'
                                };
                            };
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: ':visible:not(:last-child)',
                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            }
                        },
                        title: '{{ $title }}',
                        messageTop: '{{ $message }}',
                        customize: function(doc) {
                            doc.header = {
                                text: '{{ $title }}',
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            };
                            doc.footer = {
                                text: 'Page ' + doc.pageNumber + ' of ' + doc.pageCount,
                                alignment: 'center'
                            };
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
    @endpush
</div>
