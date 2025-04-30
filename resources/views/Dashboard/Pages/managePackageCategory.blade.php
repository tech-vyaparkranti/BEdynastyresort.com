@extends('layouts.dashboardLayout')
@section('title', 'Package Category')
@section('content')

    <x-dashboard-container container_header="Manage Package Category">
        <x-card>
            <x-card-header>Add Package Category</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                    <x-select-label-group   required name="category_name" id="category_name" label_text="Category Name">
                        @if (!empty($categories))
                            @foreach ($categories as $item)
                                <option value="{{$item}}">{{$item}}</option>
                            @endforeach
                        @endif
                    </x-select-label-group>
                    <x-select-label-group   required   name="package_id[]" id="package_id" data-placeholder="Select Packages" multiple label_text="Package">
                        @if (!empty($packages))
                            @foreach ($packages as $item)
                                <option value="{{$item->id}}">{{$item->package_name}}</option>
                            @endforeach
                        @endif
                    </x-select-label-group>
                    <x-input-with-label-element id="position"  label="Position Number" type="numeric" minVal="1"
                    placeholder="Position Number" name="position" ></x-input-with-label-element>

                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-header>Package Category Data</x-card-header>
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
                    url: "{{ route('packageCategoryData') }}",
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: "Action"
                    },
                    {
                        data: 'id',
                        name: 'id',
                        title: "Id"
                    },
                    {
                        data: 'category_name',
                        name: 'category_name',
                        title: "Category Name"
                    },                     
                    {
                        data: 'package.package_name',
                        name: 'package.package_name',
                        title: "Package Name"
                    },                     
                                     
                    {
                        data: 'position',
                        name: 'position',
                        title: "Position"
                    }
                    
                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#category_name").val(row['category_name']);
                $("#package_id").val(row['package_id']).select2();
                $("#position").val(row['position']);
                $("#action").val("update");
                scrollToDiv();
            }

        });
        $(document).ready(function() {
            $("#submit_form").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("addPackageCategoryData") }}',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            successMessage(response.message, true);
                            table.ajax.reload();
                        } else {
                            errorMessage(response.message);
                        }
                    },
                    failure: function(response) {
                        errorMessage(response.message);
                    }
                });
            });
        });

         
        $("#package_id").select2();
        function enableDisable(id,action) {
            if (id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text:( action=="disable"?"This item will be disabled!":"This item will be enabled!"),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, '+action+' it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route("addPackageCategoryData") }}',
                            data: {
                                id: id,
                                action: action,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status) {
                                    successMessage(response.message);
                                    table.ajax.reload()
                                } else {
                                    errorMessage(response.message);
                                }
                            },
                            failure: function(response) {
                                errorMessage(response.message);
                            }
                        });
                    }
                });

            } else {
                errorMessage("Something went wrong. Code 102");
            }
        }
        

        function Disable(id){
            enableDisable(id,"disable");
        }
        function Enable(id){
            enableDisable(id,"enable")
        }
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
