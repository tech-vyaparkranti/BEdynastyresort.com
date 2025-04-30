@extends('layouts.dashboardLayout')
@section('title', 'Services')
@section('content')

    <x-content-div heading="Manage Services">
        <x-card-element header="Add Services">
            <x-form-element method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                <x-input type="hidden" name="id" id="id" value=""></x-input>
                <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                <x-input-with-label-element id="service_name" label="Service Name" placeholder="Service Name"
                    name="service_name" required></x-input-with-label-element>
                
                <x-input-with-label-element div_id="service_image_div" id="service_image" label="Multiple Service Image" type="file" accept="image/*"
                placeholder="Service Image" name="service_image[]" required="true" multiple></x-input-with-label-element>

                <x-input-with-label-element div_id="banner_image_div" id="banner_image" label="Banner Image" type="file" accept="image/*"
                placeholder="Banner Image" name="banner_image"></x-input-with-label-element>

                <x-input-with-label-element id="sorting_number" label="Sorting Number" type="numeric" minVal="1"
                    placeholder="Sorting Number" name="position" required="true"></x-input-with-label-element>

                <x-select-with-label id="category" name="category" label="Select Category" required="true">
                        <option value="Content Marketing">Content Marketing</option>
                        <option value="Social Marketing">Social Marketing</option>
                        <option value="App Development">App Development</option>
                        <option value="SEO Optimization">SEO Optimization</option>
                        <option value="Web Development">Web Development</option>
                        <option value="PPC Advertising">PPC Advertising</option>
                </x-select-with-label>
                <x-text-area-with-label div_class="col-md-12 col-sm-12 mb-3" id="service_details"
                    placeholder="Service Details" label="Service Details" name="service_details"  ></x-text-area-with-label>

                <x-text-area-with-label div_class="col-md-12 col-sm-12 mb-3" id="short_description"
                    placeholder="Short Description" label="Short Description" name="short_desc"  ></x-text-area-with-label>

                
    
                <x-form-buttons></x-form-buttons>
            </x-form-element>

        </x-card-element>

        <x-card-element header="Services Data">
            <x-data-table>

            </x-data-table>
        </x-card-element>
    </x-content-div>
@endsection

@section('script')

    <script type="text/javascript">
        $('#service_details').summernote({
            placeholder: 'Service Details',
            tabsize: 2,
            height: 100
        });
        $('#short_description').summernote({
            placeholder: 'Short Description',
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
                    url: "{{ route('ourServicesData') }}",
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
                        visible: false,
                        width: '20%'
                    },
                    {
                        data: 'service_name',
                        name: 'service_name',
                        title: 'Service Name',
                        width: '20%'
                    },
                    {
                        data: 'banner_image',
                        render: function(data, type, row) {
                            let image = '';
                            if (data) {
                                image = '<image  class="img-thumbnail" src="'+data+'">'
                            }
                            return image;
                        },
                        orderable: false,
                        searchable: false,
                        title: "Banner Image",
                        width: '30%'
                    },
                    {
                        data: 'service_image',
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
                        title: "Service Images",
                    },
                    {
                        data: 'service_details',
                        name: 'service_details',
                        title: 'Service Details',
                        width: '30%'
                    },
                    {
                        data: 'short_desc',
                        name: 'short_desc',
                        title: 'Short Description',
                        width: '10%'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        title: 'Category',
                    },
                    {
                        data: 'position',
                        name: 'position',
                        title: 'Service Position',
                        width: '20%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: 'Action',
                        width: '20%'
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
                $("#service_image_old_div").remove();
                $("#id").val(row['id']);
                $("#service_image").attr("required", false);
                $("#banner_image").attr("required", false);
                $("#service_name").val(row['service_name']);                
                $("#service_image_div").parent().append(`
                <div class="col-md-4 col-sm-12 mb-3" id="service_image_old_div">
                    <label class="form-label" for="service_image_old">Current Service Image</label>            
                        <input class="form-control" type="image" src="${row['banner_image']}" id="service_image_old" label=" Old Banner Image "   >
                </div>`);
                $("#sorting_number").val(row['position']);
                $("#service_details").val(row['service_details']);
                $("#category").val(row['category']);
                $('#service_details').summernote('destroy');
                $('#service_details').summernote({focus: true});
                $("#short_description").val(row['short_desc']);
                $('#short_description').summernote('destroy');
                $('#short_description').summernote({focus: true});
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
                            url: '{{ route('saveOurServicesMaster') }}',
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
                    url: '{{ route('saveOurServicesMaster') }}',
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

            function formatIcon(icon) {
                console.log(icon);
                var $iconImg = $(
                    '<span><i class="' + icon.text + '"></i>' + icon.text + '</span>'
                );
                return $iconImg;
            }
           
        });
    </script>
    @include('Dashboard.include.dataTablesScript')
    {{-- @include('Dashboard.include.summernoteScript') --}}
@endsection
