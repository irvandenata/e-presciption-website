@extends('layouts.template')

@section('title', $title)

@push('css')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
@endpush

@push('style')
    <style>
        .mtop-100 {
            margin-top: 150px !important;
        }

        .modal-lg {
            max-width: 80% !important;
        }

    </style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">

            <table id="datatable" class="table table-bordered  m-t-30">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th>Tanggal Resep</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    @include('user.resep._form')
</div>
@endsection

@push('js')

    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweet-alert/sweetalert2.min.js') }}"></script>
    {{-- sweat allert --}}

    <!-- Responsive examples -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Selection table -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.select.min.js') }}"></script>

@endpush

@push('script')

    @include('crud.js')
    <script>
        let dataTable = $('#datatable').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                className: 'btn btn-success btn-sm mr-2',
                text: 'Create',
                action: function (e, dt, node, config) {
                    location.href = '{{ route('resep.create') }}';
                }
            }, {
                className: 'btn btn-warning btn-sm mr-2',
                text: 'Reload',
                action: function (e, dt, node, config) {
                    reloadDatatable();
                    Toast.fire({
                        icon: 'success',
                        title: 'Reload'
                    })
                }
            }],
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            ajax: {
                url: child_url,
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false
                },
                {
                    data: 'created_at',
                    orderable: true
                },
                {
                    data: 'action',
                    name: '#',
                    orderable: false
                },
            ]
        });

    </script>

    <script>
        function deleteItem(id) {
            deleteConfirm(id)

        }
        async function detailItem(id) {
            await $('#detailtable').dataTable().fnClearTable();
            await $('#detailtable').dataTable().fnDestroy();
            $("#modalForm").modal('show');
            $('#detailtable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                searching: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 15, -1],
                    [5, 10, 15, "All"]
                ],
                ajax: {
                    url: 'api/resep/detail/' + id,
                    type: 'GET',
                },
                columns: [{
                        data: 'nama_obat',
                        orderable: true
                    },
                    {
                        data: 'jenis_obat',
                        orderable: true
                    },
                    {
                        data: 'signa',
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        orderable: false
                    },
                ]
            });
        }

    </script>

    <script>
        /** set data untuk edit**

        /** reload dataTable Setelah mengubah data**/
        function reloadDatatable() {
            dataTable.ajax.reload();
        }

    </script>

@endpush
