@extends('layouts.dashboardLayout')
@section('title', 'Manage Gallery')
@section('content')

    <x-dashboard-container container_header="Manage Gallery">
        <x-card>
            <x-card-header>Add Gallery Items</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                    <x-input-with-label-element name="image" id="local_image" type="file" label="Upload Images"
                        placeholder="Images" accept="image"></x-input-with-label-element>
                    <x-input-with-label-element div_class="hidden col-md-4 col-sm-12 mb-3" div_id="old_image_div"
                        type="image" name="old_image" id="old_image" placeholder="Old Image"
                        label="Old Image"></x-input-with-label-element>
                    <x-input-with-label-element type="url" name="video_link" id="video_link" placeholder=""
                        label="You Tube Video Link"></x-input-with-label-element>

                    <x-input-with-label-element type="text" id="title" name="title"
                        placeholder="Gallery Item Title" label="Title"></x-input-with-label-element>

                    <x-select-with-label id="category" name="category" label="Select Category" required="true">
                        <option value="SUPER DELUXE LAKE VIEW">SUPER DELUXE LAKE VIEW</option>
                        <option value="SUPER DELUXE ROOM">SUPER DELUXE ROOM</option>
                        <option value="EXECUTIVE ROOM">EXECUTIVE ROOM</option>
                        <option value="PENTHOUSE">PENTHOUSE</option>
                        <option value="MAHARAJA SINGLE">MAHARAJA SINGLE</option>
                        <option value="FAMILY ROOM">FAMILY ROOM</option>
                        <option value="MAHARAJA DOUBLE">MAHARAJA DOUBLE</option>
                    </x-select-with-label>

                    <x-text-area-with-label div_class="col-md-12 col-sm-12 mb-3" id="short_detail"
                        placeholder="Image Short Detail" label="Image Short Detail" name="details">
                    </x-text-area-with-label>

                    <x-select-label-group required name="status" id="view_status" label_text="View Status">
                        <option value="1">Visibile</option>
                        <option value="0">Hidden</option>
                    </x-select-label-group>
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-header>Gallery Data</x-card-header>
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
                    url: "{{ route('galleryData') }}",
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
                        data: 'title',
                        name: 'title',
                        title: "Title"
                    },
                    
                    {
                        data: 'image',
                        render: function(data, type) {
                            let image = '';
                            if (data) {
                                image += '<img alt="Stored Image" src="' + data +
                                    '" class="img-thumbnail">';
                            }
                            return image;
                        },
                        orderable: false,
                        searchable: false,
                        title: "Image"
                    },
                    {
                        data: 'details',
                        name: 'details',
                        title: "Details"
                    },
                    {
                        data: 'category',
                        name: 'category',
                        title: "Category"
                    },

                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#title").val(row['title']);
                $("#category").val(row['category']);
                $("#view_status").val(row['status']);
                $("#video_link").val(row['video_link']);
                $("#action").val("update");
                $("#old_image").prop('src',row['image']);

                $("#short_detail").val(row['details']);
                $('#short_detail').summernote('destroy');
                $('#short_detail').summernote({focus: true});

                $("#old_image_div").removeClass("hidden");
                scrollToDiv();
            }

        });
        $(document).ready(function() {
            $("#submit_form").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('saveGallery') }}',
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

        function deleteGallery(id) {
            if (id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This item will be removed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('saveGallery') }}',
                            data: {
                                id: id,
                                action: "delete",
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
                            url: '{{ route('saveGallery') }}',
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
