@extends('layouts.dashboardLayout')
@section('title', 'Donation')
@section('content')

    <x-content-div heading="Manage Donation">
        <x-card-element header="Add  Images">
            <x-form-element method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                <x-input type="hidden" name="id" id="id" value=""></x-input>
                <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                <x-input-with-label-element div_id="image_div" id="image" label="Upload QR Code Image" name="image" type="file" accept="image/*"
                    required></x-input-with-label-element>

                <x-text-area-with-label id="heading_top" label="Services"
                    name="heading_top"></x-text-area-with-label>

                {{-- <x-input-with-label-element id="heading_middle" label="Middle Heading Text"
                    name="heading_middle"></x-input-with-label-element> --}}

                {{-- <x-input-with-label-element id="heading_bottom" label="Bootom Heading Text"
                    name="heading_bottom"></x-input-with-label-element> --}}

                <x-select-with-label id="slide_status" name="slide_status" label="Select Status" required="true">
                    <option value="live">Live</option>
                    <option value="disabled">Disabled</option>
                </x-select-with-label>
                <x-input-with-label-element id="slide_sorting" label="Sorting" type="numeric"
                name="slide_sorting"></x-input-with-label-element>

                <x-form-buttons></x-form-buttons>
            </x-form-element>

        </x-card-element>

        <x-card-element header="Donate Data">
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
            $('#heading_top').summernote({
                placeholder: 'ElementText',
                tabsize: 2,
                height: 100
            });
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
                ajax: {
                    url: "{{ route('donateData') }}",
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
                        data: '{{ \App\Models\DonateModel::ID }}',
                        name: '{{ \App\Models\DonateModel::ID }}',
                        title: 'Id',
                        visible: false
                    },
                    {
                        data: '{{ \App\Models\DonateModel::IMAGE }}',
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
                        title: "QR Code"
                    },
                    {
                        data: '{{ \App\Models\DonateModel::HEADING_TOP }}',
                        name: '{{ \App\Models\DonateModel::HEADING_TOP }}',
                        title: 'Services'
                    },
                    {
                        data: '{{ \App\Models\DonateModel::SLIDE_STATUS }}',
                        name: '{{ \App\Models\DonateModel::SLIDE_STATUS }}',
                        title: 'Status'
                    },
                    {
                        data: '{{ \App\Models\DonateModel::SLIDE_SORTING }}',
                        name: '{{ \App\Models\DonateModel::SLIDE_SORTING }}',
                        title: 'Sorting'
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
                $("#image_old_div").remove();
                $("#id").val(row['id']);
                $("#image").prop("required",false);
                $("#image_div").parent().append(`
                <div class="col-md-4 col-sm-12 mb-3" id="image_old_div">
    <label class="form-label" for="image_old">Current Service Image</label>            
        <input class="form-control" type="image" src="${site_url+row['image']}" id="image_old" label="Image Old"   >
</div>`);
                // $("#heading_top").val(row['heading_top']);
                $("#heading_top").val(row['heading_top']);
                $('#heading_top').summernote('destroy');
                $('#heading_top').summernote({focus: true});
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
                            url: '{{ route('saveDonate') }}',
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
                    url: '{{ route('saveDonate') }}',
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
