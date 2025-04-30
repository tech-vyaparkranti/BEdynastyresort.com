@extends('layouts.dashboardLayout')
@section('title', 'Download File')
@section('content')

    <x-content-div heading="Download File">
        <x-card>
            <x-card-header>Upload Files</x-card-header>
            <x-card-body>
                <x-form method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>
                    <x-input-with-label-element name="local_file" id="local_file" type="file"
                        label="Upload File" placeholder="Files" accept="pdf/*"
                        multiple></x-input-with-label-element>
                        <x-input-with-label-element name="image" id="local_image" type="file"
                        label="Upload Image" placeholder="Image" accept="image/*"
                        multiple></x-input-with-label-element>
                    {{-- <x-input-with-label-element div_class="hidden col-md-4 col-sm-12 mb-3" div_id="old_image_div" type="file" name="old_image" id="old_image" placeholder="Old Image"
                        label="Old Image"></x-input-with-label-element>
                    <x-input-with-label-element type="url" name="image_link" id="image_link" placeholder="Image Link"
                        label="Image Link"></x-input-with-label-element> --}}

                        {{-- <x-input-with-label-element required name="alternate_text" id="alternate_text"
                        placeholder="Alernate Text For File" label="Alternate Text"></x-input-with-label-element> --}}

                        <x-input-with-label-element type="text" id="title" name="title"
                        placeholder="File Title" label="Title"></x-input-with-label-element>

                    <x-select-label-group name="status" id="view_status" label_text="View Status">
                        <option value="enable">Enable</option>
                        <option value="disable">Disable</option>
                    </x-select-label-group>
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>

        <x-card-element header="Download Data">
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
        let site_url = '{{ url('/') }}';
        let table = "";
        $(function() {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
                "paging": true,
                ajax: {
                    url: "{{ route('getDownloadFile') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        title: 'Id',
                        visible: false,
                        width: '20%'
                    },
                    {
                        data: '{{ \App\Models\DownloadFile::ID }}',
                        name: '{{ \App\Models\DownloadFile::ID }}',
                        title: "Id"
                    },
                    {
                        data: '{{ \App\Models\DownloadFile::LOCAL_FILE }}',
                        name: '{{ \App\Models\DownloadFile::LOCAL_FILE }}',
                        title: "LOCAL FILE PATH" ,
                        width: '20%',
                    },  
                    {
                        data: '{{ \App\Models\DownloadFile::IMAGE }}',
                        render: function(data, type) {
                            let image = '';
                            if (data) {
                                console.log('Image Data:', data);
                                image += '<img alt="Stored Image" src="' + site_url + data  +'" class="img-thumbnail">';
                            }
                            return image;
                        },
                        title: "IMAGE"
                    },                     
                    {
                        data: '{{ \App\Models\DownloadFile::TITLE }}',
                        name: '{{ \App\Models\DownloadFile::TITLE }}',
                        title: "TITLE"
                    },                     
                                     
                    {
                        data: '{{ \App\Models\DownloadFile::SORTING }}',
                        name: '{{ \App\Models\DownloadFile::SORTING }}',
                        title: "SORTING"
                    },
                    {
                        data: '{{ \App\Models\DownloadFile::STATUS }}',
                        name: '{{ \App\Models\DownloadFile::STATUS }}',
                        title: "Status"
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
        if (row['id'])
            {
                $("#image").remove();
                $("#id").val(row['id']);
                $("#image").prop("required",false);
                $("#id").val(row['id']); 
                $("#image").val(row['image']);                
        //         $("#local_image").parent().append(`
        //     <div class="col-md-4 col-sm-12 mb-3" id="image_old_div">
        //         <label class="form-label" for="image_old">Current Upload Image</label>
        //         <img class="img-thumbnail" src="${site_url + row.image}" alt="Stored Image">
        //     </div>
        // `);
                $("#title").val(row['title']);
                $("#view_status").val(row['status']);   
                $('#image').val(row['image']);                           
                $("#action").val("update");
                scrollToDiv();
        } 
        else {
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
                            url: '{{ route('uploadData') }}',
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
                    url: '{{ route('uploadData') }}',
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
