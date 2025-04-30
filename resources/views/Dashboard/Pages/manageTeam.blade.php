@extends('layouts.dashboardLayout')
@section('title', 'Manage Team Data')
@section('content')

    <x-content-div heading="Manage Team Data">
        <x-card-element header="Add Team Information ">
            <x-form-element method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                <x-input type="hidden" name="id" id="id" value=""></x-input>
                <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                <x-input-with-label-element name="image" id="image" type="file" label="Upload Images"
                    placeholder="Images" accept="image"></x-input-with-label-element>

                <x-input-with-label-element required name="name" id="name" placeholder="Team Member Name"
                    label="Team Member Name"></x-input-with-label-element>

                <x-input-with-label-element required name="designation" id="designation" placeholder="Designation"
                    label="Designation"></x-input-with-label-element>

                <x-input-with-label-element type="number" id="position" name="position" placeholder="Position"
                    label="Position"></x-input-with-label-element>

                <x-input-with-label-element name="facebook_link" id="facebook" placeholder="Facebook Link"
                    label="Facebook Link"></x-input-with-label-element>
                <x-input-with-label-element name="instagram_link" id="instagram_link" placeholder="Instagram Link"
                    label="Instagram Link"></x-input-with-label-element>

                <x-input-with-label-element name="twitter_link" id="twitter" placeholder="Twitter Link "
                    label="Twitter Link"></x-input-with-label-element>

                <x-input-with-label-element name="linkedin_link" id="linkedin" placeholder="Linkedin Link"
                    label="Linkedin Link"></x-input-with-label-element>
                <x-select-with-label id="team_status" name="status" label="Select Status" required="true">
                    <option value="1">Live</option>
                    <option value="0">Disabled</option>
                </x-select-with-label>
                <x-form-buttons></x-form-buttons>
            </x-form-element>

        </x-card-element>

        <x-card-element header="Team Data">
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
                    url: "{{ route('getTeamInfo') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        title: "Id"
                    },
                    {
                        data: 'name',
                        name: 'name',
                        title: "Name"
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
                        title: "Image Local"
                    },
                    {
                        data: 'designation',
                        name: 'designation',
                        title: "Designation"
                    },
                    {
                        data: 'position',
                        name: 'position',
                        title: "Sorting"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: "Action"
                    },
                ]


            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#name").val(row['name']);
                $("#title").val(row['title']);
                $("#position").val(row['position']);
                $("#designation").val(row['designation']);
                $("#facebook").val(row['facebook_link']);
                $("#twitter").val(row['twitter_link']);
                $("#linkedin").val(row['linkedin_link']);
                $("#instagram_link").val(row['instagram_link']);
                $("#team_status").val(row['status']);
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
                            url: '{{ route('saveTeamInfo') }}',
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
                    url: '{{ route('saveTeamInfo') }}',
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
