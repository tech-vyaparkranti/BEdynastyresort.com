@extends('layouts.dashboardLayout')
@section('title', 'Contact Us')
@section('content')

    <x-dashboard-container container_header="Manage Contact Us">
        <x-card>
            <x-card-header>Contact Us Data</x-card-header>
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
                    url: "{{ route('contactUsDataTable') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                "scrollX": true,
                "order": [
                    [1, 'desc']
                ],
                columns: [ 
                    {
                        data: 'id',
                        name: 'id',
                        title: "Id"
                    },
                    {
                        data: 'name',
                        name: 'name',
                        title: "Name"
                    }, 
                    {
                        data:'address',
                        name:'address',
                        title:'Address'
                    } ,                   
                    
                    {
                        data: 'phone_number',
                        name: 'phone_number',
                        title: "Phone Number"
                    },
                    {
                        data: 'message',
                        name: 'message',
                        title: "Message"
                    },
                    {
                        data: 'created_at_formatted',
                        name: 'created_at',
                        title: "Date and Time"
                    }
                ]
            });

        });
         
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
