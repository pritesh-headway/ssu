@extends('layouts.master')
@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    {{-- <a style="float: right;" href="{{ route('customer.create')}}" class="btn btn-primary">Add Customer</a> --}}
    <div class="container mt-5">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <button id="unassignCoupons" class="btn btn-danger btn-sm">Unassign Selected Coupons</button>
        {{-- <a class="btn btn-primary" id="changeLabel" onclick="show('Show Graph Report')">Show Graph Report</a> --}}
        <table id="myTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Store Name</th>
                    <th>Customer Name</th>
                    <th>Customer Contact</th>
                    <th>Coupon Number</th>
                    <th class="noExport">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div id="graphshow">
            <canvas id="myChart"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
<script type="text/javascript">
    function show(txt) {
        if(txt === 'Show Graph Report') {
            txt = 'Show Table Data';
            $("#changeLabel").attr("onclick","show('Show Table Data')");
        } else {
            txt = 'Show Graph Report';
            $("#changeLabel").attr("onclick","show('Show Graph Report')");
        }
        $("#graphshow").toggle();
        $("#changeLabel").text(txt);
        $("#myTable").toggle();
        $("#myTable_filter").toggle();
        $(".dataTables_info").toggle();
    }

    $(function() {
        $("#graphshow").hide();
        gb_DataTable = $("#myTable").DataTable({
            autoWidth: false,
            order: [0, "ASC"],
            processing: true,
            serverSide: true,
            searchDelay: 2000,
            paging: false,
            ajax: "{{ route('reportssellercustomer.show', $id) }}",
            iDisplayLength: "10000",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id',
                    render: function(data, type, row) {
                    return '<input type="checkbox" class="select-row" value="' + row.id + '">';
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'storename',
                    name: 'storename'
                },
                {
                    data: 'CustomerName',
                    name: 'CustomerName'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number'
                },
                {
                    data: 'coupon_number',
                    name: 'coupon_number'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    className:'btn btn-success btn-sm',
                    text: 'Export to Excel',
                    filename: 'Seller_Customer_Coupons_Wise_Data', // Custom file name for Excel
                    title: 'Seller Customer Coupons Wise Data',
                    exportOptions: {
                        columns: ':not(.noExport)'
                    }
                }
            ],
            lengthMenu: [25, 50, 100]
        });
    });

    function deleteItem(id, rowId) {
        if (confirm('Are you sure you want to un-assign this coupons?')) {
            $.ajax({
                url: id +'/' + rowId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#myTable').DataTable().ajax.reload();
                }
            });
        }
    }

    $(document).on('click', '#unassignCoupons', function() {
        let selectedIds = [];
        $('.select-row:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            alert('Please select at least one row to unassign.');
            return;
        }
        
        // Confirm action
        if (!confirm('Are you sure you want to unassign the selected coupons?')) {
            return;
        }
    
        // AJAX request to unassign coupons
        $.ajax({
            url: "{{ route('customercoupon.unassign') }}", // Add your unassign route here
            method: 'POST',
            data: {
                ids: selectedIds,
                _token: "{{ csrf_token() }}" // Laravel CSRF token for security
            },
            success: function(response) {
                // gb_DataTable.ajax.reload(); // Reload DataTable
                alert('Selected coupons have been unassigned successfully!');
                $('#myTable').DataTable().ajax.reload();
            },
            error: function(xhr) {
                alert('An error occurred while unassigning coupons.');
            }
        });
    });
</script>
@endsection