@extends('layouts.dashboardLayout')
@section('title', 'Destination')
@section('content')
    <x-dashboard-container container_header="Manage Destination">
        <x-card>
            <x-card-header>Add Destination Master</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                    <x-input-with-label-element required name="package_name" id="package_name" placeholder=" Name"
                        label="Name"></x-input-with-label-element>

                    <x-input-with-label-element name="package_image[]" id="package_image" type="file"
                        label="Upload Image" placeholder="Images" accept="image/*"
                        multiple></x-input-with-label-element>

                    <x-input-with-label-element name="package_country" id="package_country" placeholder="Location"
                        label="Location"></x-input-with-label-element>

                   
                    <x-text-area-with-label id="description" label="Description"
                        name="description"></x-text-area-with-label>
                        <x-input-with-label-element id="meta_keyword" label="Meta Keyword"
                    name="meta_keyword"></x-input-with-label-element>
                    <x-input-with-label-element id="meta_title" label="Meta Title"
                    name="meta_title"></x-input-with-label-element>
                    <x-input-with-label-element id="meta_description" label="Meta Description"
                    name="meta_description"></x-input-with-label-element>
                   
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-header>Destination Data</x-card-header>
            <x-card-body>
                <x-data-table>
                </x-data-table>
            </x-card-body>
        </x-card>
    </x-dashboard-container>
    {{-- <x-modal-component id="city_modal" modal_title="Add City">
        <x-form id="addCityForm">
            <div class="row">
                <x-input-with-label-element div_class="col-md-12" type="text" name="city_name" id="city_name_id"
                    placeholder="City Name" label="City Name"></x-input-with-label-element>

            </div>
            <div class="row mt-4">
                <x-form-buttons submit_text="Save City"></x-form-buttons>
            </div>

        </x-form>
    </x-modal-component> --}}
@endsection

@section('script')

    @include('Dashboard.include.dataTablesScript')
    <script type="text/javascript">
      $('#description').summernote({
            placeholder: 'Description',
            tabsize: 2,
            height: 100
        });
        $(function() {
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
                ajax: {
                    url: "{{ route('packageMasterDataTable') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        title: "Sr.No."
                    },
                    {
                        data: 'id',
                        name: 'id',
                        title: 'Id',
                        visible: false
                    },
                    {
                        data: 'package_name',
                        name: 'package_name',
                        title: 'Name'
                    },
                    {
                        data: 'package_image',
                        name:'package_image',
                        orderable: false,
                        searchable: false,
                        title: "Image"
                    },
                    {
                        data: 'package_country',
                        name: 'package_country',
                        title: 'Location'
                    },
                   
                    {
                        data: 'description',
                        name: 'description',
                        title: 'Description'
                    },
                    {
                        data: 'meta_keyword',
                        name: 'meta_keyword',
                        title: 'Meta Keyword'
                    },
                    {
                        data: 'meta_title',
                        name: 'meta_title',
                        title: 'Meta Title'
                    },
                    {
                        data: 'meta_description',
                        name: 'meta_description',
                        title: 'Meta Description'
                    },
                    
                    
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: 'Action'
                    },
                ],
                order: [
                    [1, "desc"]
                ]
            });

            });

        function CityMasterOptionsUpdate(data, returnOption = false, remove_selected = false) {
            let options = '<option value="">Select</option>';
            if (data.length) {
                data.forEach(element => {
                    let found = 0;
                    if (remove_selected) {
                        $(".cityList").each(function() {
                            if ($(this).val() == element.id) {
                                found = 1;
                            }
                        });
                    }

                    if (found == 0) {
                        options += '<option value="' + element.id + '">' + element.city_name + '</option>';
                    }

                });
            }
            if (returnOption) {
                return options;
            }
            $(".cityList").each(function() {
                let val = $(this).val();
                $(this).html(options).val(val).select2();
            });

        }
        $(document).ready(function() {
            $("#addCityForm").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('add-city') }}',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            successMessage(response.message);
                            $("#city_modal").modal("hide");
                            cityList = response.data;
                            CityMasterOptionsUpdate(response.data);
                        } else {
                            errorMessage(response.message);
                        }
                    },
                    failure: function(response) {
                        errorMessage(response.message);
                    }
                });
            });
            $("#submit_form").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('packageMaster.store') }}',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            successMessage(response.message, true);

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

        function enableDisable(id, action) {
            if (id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: (action == "disable" ? "This item will be disabled!" : "This item will be enabled!"),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, ' + action + ' it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('enableDisablePackage') }}',
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
        let cityList = $.parseJSON(`{!! json_encode($city_master) !!}`);

        function getCityOption() {
            return CityMasterOptionsUpdate(cityList, true, true)
        }

        function Disable(id) {
            enableDisable(id, "disable");
        }

        function Enable(id) {
            enableDisable(id, "enable")
        }
    </script>

@endsection
