@extends('layouts.dashboardLayout')
@section('title', 'Slider')
@section('content')

    <x-content-div heading="Manage Slider">
        <x-card-element header="Add Slider Images">
            <x-form-element method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                <x-input type="hidden" name="id" id="id" value=""></x-input>
                <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                <x-input-with-label-element id="image" label="Upload Slider Image" name="image" type="file" accept="image/*"
                    required></x-input-with-label-element>

                {{-- <x-input-with-label-element id="heading_top" label="Top Heading Text"
                    name="heading_top"></x-input-with-label-element> --}}

                {{-- <x-input-with-label-element id="heading_middle" label="Middle Heading Text"
                    name="heading_middle"></x-input-with-label-element> --}}

                {{-- <x-input-with-label-element id="heading_bottom" label="Bootom Heading Text"
                    name="heading_bottom"></x-input-with-label-element> --}}

                <x-select-with-label id="slide_status" name="slide_status" label="Select Slide Status" required="true">
                    <option value="live">Live</option>
                    <option value="disabled">Disabled</option>
                </x-select-with-label>
                <x-input-with-label-element id="slide_sorting" label="Slide Sorting" type="numeric"
                name="slide_sorting"></x-input-with-label-element>

                <x-form-buttons></x-form-buttons>
            </x-form-element>

        </x-card-element>

        <x-card-element header="SliderData">
            <x-data-table>

            </x-data-table>
        </x-card-element>
    </x-content-div>
@endsection

@section('script')
    <script type="text/javascript">
        let site_url = '{{ url('/') }}';
        let table = "";
        $(function() {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
                ajax: {
                    url: "{{ route('sliderData') }}",
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
                        data: '{{ \App\Models\SliderModel::ID }}',
                        name: '{{ \App\Models\SliderModel::ID }}',
                        title: 'Id',
                        visible: false
                    },
                    {
                        data: '{{ \App\Models\SliderModel::IMAGE }}',
                        render: function(data, type, row) {
                            let image = '';
                            if (data) {
                                image = '<img alt="Image Link" src="' + site_url + data +
                                    '" class="img-thumbnail">'
                            }
                            return image;
                        },
                        orderable: false,
                        searchable: false,
                        title: "Slide Image"
                    },
                    {
                        data: '{{ \App\Models\SliderModel::SLIDE_STATUS }}',
                        name: '{{ \App\Models\SliderModel::SLIDE_STATUS }}',
                        title: 'Slide Status'
                    },
                    {
                        data: '{{ \App\Models\SliderModel::SLIDE_SORTING }}',
                        name: '{{ \App\Models\SliderModel::SLIDE_SORTING }}',
                        title: 'Slide Sorting'
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
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#image").attr("required",false);
                // $("#heading_top").val(row['heading_top']);
                // $("#heading_middle").val(row['heading_middle']);
                // $("#heading_bottom").val(row['heading_bottom']);
                $("#slide_status").val(row['slide_status']);
                $("#slide_sorting").val(row['slide_sorting']);
                $("#action").val("update");

                scrollToDiv();
            } else {
                errorMessage("Something went wrong. Code 101");
            }
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
                            url: '{{ route('saveSlide') }}',
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


        $(document).ready(function() {
            $("#submitForm").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('saveSlide') }}',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            successMessage(response.message, "reload");
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
    </script>
    @include('Dashboard.include.dataTablesScript')
    {{-- @include('Dashboard.include.summernoteScript') --}}
@endsection
