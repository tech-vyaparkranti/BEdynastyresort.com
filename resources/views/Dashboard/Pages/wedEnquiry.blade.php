@extends('layouts.dashboardLayout')
@section('title', ' Wedding Enquiry')
@section('content')

    <x-dashboard-container container_header="Manage Wedding Enquiry">
        <x-card>
            <x-card-header>Wedding Enquiry Data</x-card-header>
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
                    url: "{{ route('wedEnquiryTable') }}",
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
                        data: 'your_name',
                        name: 'your_name',
                       title: "Your Name",
                        width: "15%"
                    },
                    {
                        data: 'partner_name',
                        name: 'partner_name',
                       title: "Partner Name",
                        width: "15%"
                    },                     
                    {
                        data: 'phone',
                        name: 'phone',
                        title: "Phone Number",
                        width: "15%"
                    },
                    {
                        data: 'guest_count',
                        name: 'guest_count',
                        title: "Guest Count",
                        width: "15%"
                    },
                    {
                        data: 'add_detail',
                        name: 'add_detail',
                        title: "Add Details",
                        width: "20%"
                    }
                ]
            });

        });
         
         
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
