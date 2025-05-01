@extends('layouts.dashboardLayout')
@section('title', 'Packages')
@section('content')

    <x-content-div heading="Manage Packages">
        <x-card-element header="Add Packages">
            <x-form-element method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                <x-input type="hidden" name="id" id="id" value=""></x-input>
                <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                <x-input-with-label-element id="title" label="Packages Title" placeholder="Packages Title"
                    name="title" required></x-input-with-label-element>

                {{-- <x-input-with-label-element div_id="service_image_div" id="service_image" label="Multiple Rooms Images" type="file" accept="image/*"
                placeholder="Room Images" name="images[]" required="true" multiple></x-input-with-label-element> --}}

                <x-input-with-label-element div_id="banner_image_div" id="banner_image" label="Packages Image"
                    type="file" accept="image" placeholder="Packages Image" name="image"></x-input-with-label-element>

                <x-select-with-label id="category" name="category" label="Select Packages Category" required="true">
                    @if ($category)
                        @foreach ($category as $item)
                            <option value="{{ $item->category_name }}">{{ $item->category_name }}</option>
                        @endforeach
                    @endif
                </x-select-with-label>
                <x-text-area-with-label div_class="col-md-12 col-sm-12 mb-3" id="short_description"
                    placeholder="Short Description" label="Write Short Description (under 500 word)" name="short_desc">
                </x-text-area-with-label>

                <x-text-area-with-label div_class="col-md-12 col-sm-12 mb-3" id="service_details"
                    placeholder="Package Description" label="Package Description" name="description">
                </x-text-area-with-label>

                <x-text-area-with-label div_class="col-md-12 col-sm-12 mb-3" id="features" placeholder="Features"
                    label="Write About Features" name="features">
                </x-text-area-with-label>

                {{-- <x-input-with-label-element id="size" label="Room Size (in square meter)" placeholder="Room Size"
                    name="size" required></x-input-with-label-element>

                <x-input-with-label-element id="video_link" label="Room video Link" placeholder="You tube Video Link"
                    name="video_link"></x-input-with-label-element> --}}

                <x-input-with-label-element id="person_allow" label="Allowance Detail (In Short)"
                    placeholder="Allowance Details" name="allowance_details" required></x-input-with-label-element>

                <x-input-with-label-element id="price" label="Package Price" placeholder="Price" name="price"
                    required></x-input-with-label-element>

                <x-input-with-label-element id="offer_price" label="Offer Price" placeholder="Offer Price"
                    name="offer_price" required></x-input-with-label-element>

                <x-form-buttons></x-form-buttons>
            </x-form-element>

        </x-card-element>

        <x-card-element header="Packages Data">
            <x-data-table>

            </x-data-table>
        </x-card-element>
    </x-content-div>

    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Package Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6><strong>Description</strong></h6>
                    <p id="modal-description"></p>

                    <h6><strong>Features</strong></h6>
                    <p id="modal-features"></p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script type="text/javascript">
        $('#service_details').summernote({
            placeholder: 'Packages Details',
            tabsize: 2,
            height: 100
        });

        $('#features').summernote({
            placeholder: 'Packages Features',
            tabsize: 2,
            height: 100
        });

        $('#short_description').summernote({
            placeholder: 'Packages short Details',
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
                    url: "{{ route('packageData') }}",
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
                        title: 'Action',
                        width: '20%'
                    },
                    {
                        data: 'title',
                        name: 'title',
                        title: 'Title',
                        width: '20%'
                    },
                    {
                        data: 'image',
                        render: function(data, type, row) {
                            let image = '';
                            if (data) {
                                image = '<image  class="img-thumbnail" src="' + data + '">'
                            }
                            return image;
                        },
                        orderable: false,
                        searchable: false,
                        title: "Image",
                        width: '30%'
                    },
                    // {
                    //     data: 'images',
                    //     render: function(data, type, row) {
                    //         let images = '';

                    //         if (data) {
                    //             try {
                    //                 let cleanData = data.replace(/&quot;/g, '"');
                    //                 if (!cleanData.startsWith('[') || !cleanData.endsWith(']')) {
                    //                     cleanData = `[${cleanData}]`;
                    //                 }

                    //                 let imageArray = JSON.parse(cleanData);

                    //                 images += '<div style="display: flex; flex-wrap: wrap;">';

                    //                 imageArray.forEach(function(image) {
                    //                     images += '<img class="img-thumbnail" src="' +
                    //                         image +
                    //                         '" alt="Image" style="width: 100px; margin-right: 5px; height: auto;">';
                    //                 });
                    //             } catch (e) {
                    //                 images = '<span class="text-danger">Invalid image data</span>';
                    //             }
                    //         }

                    //         return images;
                    //     },
                    //     orderable: false,
                    //     searchable: false,
                    //     title: "Room Images",
                    // },
                    {
                        data: 'offer_price',
                        name: 'offer_price',
                        title: 'Offer Price',
                    },
                    {
                        data: 'price',
                        name: 'price',
                        title: 'Price',
                    },
                    {
                        data: null,
                        title: 'View',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button class="btn btn-info btn-sm view-details" 
                                data-description="${row.description}" 
                                data-features="${row.features}">
                                View Details
                            </button>`;
                        }
                    },

                    // {
                    //     data: 'description',
                    //     name: 'description',
                    //     title: 'Package description',
                    //     width: '30%'
                    // },
                    // {
                    //     data: 'features',
                    //     name: 'features',
                    //     title: 'Features',
                    // },
                    {
                        data: 'short_desc',
                        name: 'short_desc',
                        title: 'Short Description',
                        width : '200px',
                    },

                    {
                        data: 'allowance_details',
                        name: 'allowance_details',
                        title: 'Allowance Details',
                    },
                    {
                        data: 'category',
                        name: 'category',
                        title: 'Category',
                    },

                ],
                order: [
                    [1, "desc"]
                ]
            });

        });

        $(document).on('click', '.view-details', function() {
            const description = $(this).data('description');
            const features = $(this).data('features');

            $('#modal-description').text(description);
            $('#modal-features').text(features);

            $('#detailsModal').modal('show');
        });


        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#service_image_old_div").remove();
                $("#id").val(row['id']);
                $("#service_image").attr("required", false);
                $("#banner_image").attr("required", false);
                $("#title").val(row['title']);
                $("#service_image_div").parent().append(`
                <div class="col-md-4 col-sm-12 mb-3" id="service_image_old_div">
                    <label class="form-label" for="service_image_old">Current Image</label>            
                        <input class="form-control" type="image" src="${row['image']}" id="service_image_old" label=" Old Banner Image "   >
                </div>`);
                $("#price").val(row['price']);
                $("#offer_price").val(row['offer_price']);
                $("#person_allow").val(row['allowance_details']);

                $("#service_details").val(row['description']);
                $('#service_details').summernote('destroy');
                $('#service_details').summernote({
                    focus: true
                });

                $("#short_description").val(row['short_desc']);
                $('#short_description').summernote('destroy');
                $('#short_description').summernote({
                    focus: true
                });

                $("#features").val(row['features']);
                $('#features').summernote('destroy');
                $('#features').summernote({
                    focus: true
                });

                $("#category").val(row['category']);

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
                            url: '{{ route('savePackages') }}',
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
                    url: '{{ route('savePackages') }}',
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
@endsection
