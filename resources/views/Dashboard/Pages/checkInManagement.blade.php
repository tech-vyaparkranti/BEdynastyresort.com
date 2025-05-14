@extends('layouts.dashboardLayout')
@section('title', 'CheckIn Management')
@section('content')

    <x-dashboard-container container_header="Manage Check In Data">
        <x-card>
            <x-card-header>Check In Data</x-card-header>
            <x-card-body>
                <x-data-table></x-data-table>
            </x-card-body>
        </x-card>
    </x-dashboard-container>
@endsection

@section('script')
    <script type="text/javascript">
        let site_url = '{{ url('/') }}';
        var table = "";
        $(function() {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('checkInDataTable') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                "scrollX": true,
                "order": [
                    // [1, 'desc']
                    [0, 'desc']
                ],
                columns: [ 
                    {
                        data: 'id',
                        name: 'id',
                         title: "ID",
                        width: "5%"
                    },
                    {
                        data: 'checkIn_formatted',
                        name: 'checkIn',
                       title: "Check In Date",
                        width: "15%"
                    },
                    {
                        data: 'checkOut_formatted',
                        name: 'checkOut',
                       title: "Check Out Date",
                        width: "15%"
                    },                     
                    {
                        data: 'name',
                        name: 'name',
                        title: "Name",
                        width: "15%"
                    },
                    {
                        data: 'phone_no',
                        name: 'phone_no',
                        title: "Phone Number",
                        width: "15%"
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address',
                        title: "Ip Address",
                        width: "20%"
                    }
                ]
            });

        });
         
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
