@extends('layouts.dashboardLayout')
@section('title', 'Manage Category')
@section('content')

    <x-dashboard-container container_header="Manage Category">
        <x-card>
            <x-card-header>Add Category</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                    <x-select-with-label id="tab_name" name="tab_name" label="Category For" required="true">
                        <option value="Room">Room</option>
                        <option value="Packages">Packages</option>
                        <option value="Guest Experience">Guest Experience</option> 
                        <option value="Services">Services</option> 
                        <option value="Gallery">Gallery</option> 
                        <option value="Blog">Blog</option> 
                        <option value="Restaurant">Restaurant</option> 
                    </x-select-with-label>

                    <x-input-with-label-element type="text" name="category_name" id="category_name" placeholder=""
                        label="Category Name"></x-input-with-label-element>

                    <x-select-label-group required name="status" id="view_status" label_text="View Status">
                        <option value="1">Visibile</option>
                        <option value="0">Hidden</option>
                    </x-select-label-group>
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-header>Category Data</x-card-header>
            <x-card-body>
                <x-data-table></x-data-table>
            </x-card-body>
        </x-card>
    </x-dashboard-container>
@endsection

@section('script')
    <script type="text/javascript">
        $('#short_detail').summernote({
            placeholder: 'Image Short Details',
            tabsize: 2,
            height: 100
        });
        let site_url = '{{ url('/') }}';
        var table = "";
        $(function() {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('categoryData') }}",
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
                        data: 'tab_name',
                        name: 'tab_name',
                        title: "Category For"
                    },
                    
                    

                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                
                $("#view_status").val(row['status']);
                $("#category_name").val(row['category_name']);
                $("#tab_name").val(row['tab_name']);
                $("#action").val("update");
                scrollToDiv();
            }

        });
        $(document).ready(function() {
            $("#submit_form").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('saveCategory') }}',
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


        function Disable(id) {
            changeAction(id, "disable", "This item will be disabled!", "Yes, disable it!");
        }

        function Enable(id) {
            changeAction(id, "enable", "This item will be enabled!", "Yes, enable it!");
        }

        function changeAction(id, action, text, confirmButtonText) {
            if (id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmButtonText
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('saveCategory') }}',
                            data: {
                                id: id,
                                action: action,
                                '_token': '{{ csrf_token() }}'
                            },
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
                    }
                });
            } else {
                errorMessage("Something went wrong. Code 102");
            }
        }
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
