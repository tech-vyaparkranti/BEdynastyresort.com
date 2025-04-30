@extends('layouts.dashboardLayout')
@section('title', 'Manage New Letter')
@section('content')

    <x-dashboard-container container_header="Manage New Letter">
        
        <x-card>
            <x-card-header>News Letter Data</x-card-header>
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

            new DataTable('#example', {
    layout: {
        topStart: {
            buttons: ['csv', 'excel', 'pdf', 'print']
        }
    }
});

            table = $('.data-table').DataTable({
                layout: {
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('getNewsLetterData') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                "scrollX": true,
                "order": [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        title: "Sr.No."
                    },
                    {
                        data: 'id',
                        name: 'id',
                        title: "Id"
                    },
                    {
                        data: 'email_id',
                        name: 'email_id',
                        title: "Email Id",
                        width: "30%"
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address',
                        title: "IP Address",
                        width: "30%"
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        title: "Subscription Date",
                        width: "30%"
                    }

                ]
            });

        });
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
