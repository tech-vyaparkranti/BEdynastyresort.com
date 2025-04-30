@extends('layouts.dashboardLayout')
@section('title', 'Testimonial')
@section('content')

    <x-content-div heading="Manage Testimonial">
        <x-card-element header="Add Testimonial">
            <x-form-element method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                <x-input type="hidden" name="id" id="id" value=""></x-input>
                <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                <x-input-with-label-element id="image" label="Upload Writer Image" name="image" type="file"
                    accept="image/*" required></x-input-with-label-element>

                <x-text-area-with-label id="content" label="Testimonial Content" name="description"
                    required></x-text-area-with-label>
                <x-input-with-label-element id="name" label="Writer name" name="name" type="text"
                    required></x-input-with-label-element>
                <x-input-with-label-element id="designation" label="Writer Designation" name="designation" type="text"
                    required></x-input-with-label-element>



                <x-input-with-label-element id="sorting" required label="Testimonial Sorting" type="numeric"
                    name="sorting"></x-input-with-label-element>
                <x-input-with-label-element id="review" required label="Testimonial rating" type="numeric"
                    name="review"></x-input-with-label-element>
                <x-select-with-label id="status" name="status" label="Select Status" required="true">
                    <option value="1">Live</option>
                    <option value="0">Disabled</option>
                </x-select-with-label>

                <x-form-buttons></x-form-buttons>
            </x-form-element>

        </x-card-element>

        <x-card-element header="Testimonial Data">
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
                    url: "{{ route('getTestimonial') }}",
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
                        data: 'image',
                        render: function(data, type, row) {
                            let image = '';
                            if (data) {
                                image = '<img alt="Image Link" src="' + data +
                                    '" class="img-thumbnail">'
                            }
                            return image;
                        },
                        orderable: false,
                        searchable: false,
                        title: "Image"
                    },

                    {
                        data: 'name',
                        name: 'name',
                        title: 'Writer Name'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        title: 'Testimonial Content'
                    },

                    {
                        data: 'designation',
                        name: 'designation',
                        title: 'Writer Designation'
                    },
                    {
                        data: 'sorting',
                        name: 'sorting',
                        title: 'Sorting'
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
                $("#name").val(row['name']);
                $("#designation").val(row['designation']);
                $("#review").val(row['review']);
                $("#status").val(row['status']);
                $("#sorting").val(row['sorting']);
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
                            url: '{{ route('saveTestimonial') }}',
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
                    url: '{{ route('saveTestimonial') }}',
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
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            const allMessages = Object.values(errors).flat().join('<br>');
                            errorMessage(allMessages);
                        } else {
                            errorMessage("Something went wrong");
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
