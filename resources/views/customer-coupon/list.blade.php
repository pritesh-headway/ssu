@extends('layouts.master')
@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <form action="{{ url('import-coupons') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" required name="file"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

        <a class="btn btn-warning" href="{{ asset('sample/Customer-Coupons.xlsx') }}" style="font-size: 14px;">Download
            Sample
            Data</a>
    </form>
    <button type="button" data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-primary"
        style="font-size: 14px; float: right; margin-right:13px;" data-bs-target="#staticBackdrop">Import Assign
        Coupons</button>
    <div class="container mt-5">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <table id="myTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer Name</th>
                    <th>Assigned By</th>
                    <th>Event Year</th>
                    <th>City</th>
                    <th>Total No. of Coupons</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <!-- Modal -->
    <div class="modal fade" tabindex="-1" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="btn btn-warning" href="{{ asset('sample/Customer-Coupons.xlsx') }}"
                        style="font-size: 14px; float:right">Download
                        Sample
                        Data</a>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="importForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="importSelect" class="form-label">Select Option</label>
                            <select class="form-select" id="importSelect" name="seller_name" required>
                                <option value="" selected disabled>Select Seller</option>
                                <?php if($sellerData) { 
                                    foreach ($sellerData as $key => $value) { ?>
                                <option value="{{ $value->id }}">{{ ucwords($value->storename) }}</option>
                                <?php } } ?>
                            </select>
                            <div class="invalid-feedback">
                                Please select seller.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="importFile" class="form-label">Choose File</label>
                            <input type="file" class="form-control" id="importFile" name="import_file" required>
                            <div class="invalid-feedback">
                                Please upload a valid file.
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="importButton" class="btn btn-primary">Import</button>
                </div>

            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{ URL::to('assets/css/buttons.dataTables.min.css') }}">
<link href="{{ URL::to('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery-3.js') }}"></script>
<link href="{{ URL::to('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::to('assets/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::to('assets/js/jszip.min.js') }}"></script>
<script src="{{ URL::to('assets/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::to('assets/js/buttons.print.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="{{ URL::to('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script href="{{ URL::to('assets/js/bootstrap.min.js') }}"></script>
<script type="text/javascript">
    $(function() {
        gb_DataTable = $("#myTable").DataTable({
            autoWidth: false,
            order: [0, "DESC"],
            processing: true,
            serverSide: true,
            searchDelay: 2000,
            paging: true,
            ajax: "{{ route('customercoupon.index') }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'assigned_name',
                    name: 'assigned_name'
                },
                {
                    data: 'event_date',
                    name: 'event_date'
                },
                {
                    data: 'city',
                    name: 'city'
                },
                {
                    data: 'totalCoupon',
                    name: 'totalCoupon'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            // dom: 'Blfrtip',
            // buttons: [
            //     {
            //         extend: 'excel',
            //         text: 'Export to Excel',
            //         className:'btn btn-success btn-sm',
            //         filename: 'SSU_Customer_Coupons_Data', // Custom file name for Excel
            //         title: 'Customers Coupons Orders List',
            //         exportOptions: {
            //             columns: ':not(.noExport)'
            //         }
            //     }
            // ],
            lengthMenu: [25, 50, 100]
        });
    });

    function deleteItem(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: 'customer/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#myTable').DataTable().ajax.reload();
                    alert(response.success);
                }
            });
        }
    }

    $('#importButton').click(function (e) {
        e.preventDefault();
    
    // Validate form
        let isValid = true;
        $('#importForm').find('select, input').each(function () {
            if (!this.checkValidity()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
    
    if (!isValid) {
        return; // Stop if the form is invalid
    }
    
    // Get form data
    let formData = new FormData($('#importForm')[0]);
    
    // AJAX request
    $.ajax({
            url: "{{ url('import-coupons') }}", // Update with your route
            type: "POST",
            data: formData,
            // async:false,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            beforeSend: function () {
                $('#importButton').prop('disabled', true).text('Processing...');
            },
            success: function (response) {
                alert('Data imported successfully!');
                $('#myModal').modal('hide');
            },
            error: function (xhr) {
                alert('An error occurred. Please try again.');
            },
            complete: function () {
                $('#importButton').prop('disabled', false).text('Import');
            }
        });
    });
</script>
@endsection