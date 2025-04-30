@extends('layouts.dashboardLayout')
@section('title', 'About Us')
@section('content')

    <x-content-div heading="Manage About Us">
        <x-card-element header="Add About Us Data ">
            <x-form-element method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                <x-input type="hidden" name="id" id="id" value=""></x-input>
                <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                <x-input-with-label-element id="image" label="Upload Multiple Image" name="images[]" type="file"
                    accept="image/*" required multiple></x-input-with-label-element>
                <x-input-with-label-element id="title" label="About title" name="title"
                    required></x-input-with-label-element>
                <x-text-area-with-label id="content" label="About Description" name="description"
                    required></x-text-area-with-label>

                <x-select-with-label id="blog_status" name="status" label="Select Status" required="true">
                    <option value="1">Live</option>
                    <option value="0">Disabled</option>
                </x-select-with-label>

                <x-form-buttons></x-form-buttons>
            </x-form-element>

        </x-card-element>

        <x-card-element header="About Data">
            <x-data-table>

            </x-data-table>
        </x-card-element>
    </x-content-div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#content').summernote({
            placeholder: 'ElementText',
            tabsize: 2,
            height: 100
        });
        let site_url = '{{ url('/') }}';
        let table = "";
        $(function() {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
                ajax: {
                    url: "{{ route('getAboutData') }}",
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: 'Action'
                    },
                    {
                        data: 'images',
                        render: function(data, type, row) {
                            let images = '';

                            if (data) {
                                try {
                                    let cleanData = data.replace(/&quot;/g, '"');
                                    if (!cleanData.startsWith('[') || !cleanData.endsWith(']')) {
                                        cleanData = `[${cleanData}]`;
                                    }

                                    let imageArray = JSON.parse(cleanData);

                                    images += '<div style="display: flex; flex-wrap: wrap;">';

                                    imageArray.forEach(function(image) {
                                        images += '<img class="img-thumbnail" src="' +
                                            image +
                                            '" alt="Image" style="width: 100px; margin-right: 5px; height: auto;">';
                                    });
                                } catch (e) {
                                    images = '<span class="text-danger">Invalid image data</span>';
                                }
                            }

                            return images;
                        },
                        orderable: false,
                        searchable: false,
                        title: "Images",
                    },
                    {
                        data: 'title',
                        name: 'title',
                        title: 'About Title'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        title: 'About Content'
                    },

                ],
                order: [
                    [2, "desc"]
                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#image").attr("required", false);
                $("#title").val(row['title']);
               
                $("#blog_status").val(row['status']);
                $("#action").val("update");
                $("#content").val(row['description']);
                $('#content').summernote('destroy');
                $('#content').summernote({
                    focus: true
                });
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
                            url: '{{ route('saveAbout') }}',
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
                    url: '{{ route('saveAbout') }}',
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
@endsection
