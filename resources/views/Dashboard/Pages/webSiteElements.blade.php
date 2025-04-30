@extends('layouts.dashboardLayout')
@section('title', 'WebSite Elements')
@section('content')

<x-content-div heading="WebSiteElements" >
    <x-card-element header="Add WebSite Element" >
        <x-form-element method="POST" enctype="multipart/form-data"  id="submitForm" action="javascript:" >
            <x-input type="hidden" name="id" id="id" value="" ></x-input>
            <x-input type="hidden" name="action" id="action" value="insert" ></x-input>
                 
            <x-select-with-label id="element_id" name="element" label="Select Element" required="true" >
                @foreach ($titles as $item)
                <option value="{{$item}}">{{$item}}</option>                    
                @endforeach
            </x-select-with-label>
            
            <x-select-with-label id="element_type" name="element_type" label="Select Element Type" required="true" >
                <option value="Text">Text</option>
                <option value="Image">Image</option>
            </x-select-with-label>
            
            <x-input-with-label-element id="element_type_file" label="Upload Element details" name="element_details_image" type="file" accept="image/*"></x-input-with-label-element>
            <x-text-area-with-label div_class="col-md-12 col-sm-12 mb-3" id="element_type_text"
                    placeholder="Element details" label="Element details" name="element_details_text"></x-text-area-with-label>
            <x-form-buttons></x-form-buttons> 
        </x-form-element>

    </x-card-element>
    
    <x-card-element header="WebSite Element Data" >
        <x-data-table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Element</th>
                    <th>Element Type</th>
                    <th>Element Details</th>
                    <th width="100px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </x-data-table>
    </x-card-element>    
</x-content-div>
@endsection

@section('script')
    <script type="text/javascript">
    
    let site_url = '{{ url('/') }}';
    let table="";
        $(function() {
            $('#element_type_text').summernote({
                placeholder: 'ElementText',
                tabsize: 2,
                height: 100
            });
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('webSiteElementsData') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: '{{ \App\Models\WebSiteElements::ID }}',
                        name: '{{ \App\Models\WebSiteElements::ID }}'
                    },
                    {
                        data: '{{ \App\Models\WebSiteElements::ELEMENT }}',
                        name: '{{ \App\Models\WebSiteElements::ELEMENT }}'
                    },
                    {
                        data: '{{ \App\Models\WebSiteElements::ELEMENT_TYPE }}',
                        name: '{{ \App\Models\WebSiteElements::ELEMENT_TYPE }}'
                    },
                    {
                        data: '{{ \App\Models\WebSiteElements::ELEMENT_DETAILS }}',
                        render: function(data, type,row) {
                            let image = '';
                            if (row.element_type=="Image") {

                                image += '<img alt="Image Link" src="'+ data + '" class="img-thumbnail">';
                            }else{
                                console.log(data);
                                image = row.element_details;
                            }
                            return image;
                        },
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: '{{ \App\Models\WebSiteElements::ELEMENT_DETAILS }}',
                    //     name: '{{ \App\Models\WebSiteElements::ELEMENT_DETAILS }}'
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#element_id").val(row['element']);
                $("#element_type").val(row['element_type']);
                if(row['element_type']=="Text"){
                    $("#element_type_text").val(row['element_details']);                
                }
                $("#action").val("update");
                $('#element_type_text').summernote('destroy');
            $('#element_type_text').summernote({
                focus: true
            });
                scrollToDiv();
            } else {
                errorMessage("Something went wrong. Code 101");
            }
        });
        function Disable(id){
            changeAction(id,"disable","This item will be disabled!","Yes, disable it!");
        }
        function Enable(id){
            changeAction(id,"enable","This item will be enabled!","Yes, enable it!");
        } 
        function changeAction(id,action,text,confirmButtonText) {
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
                            url: '{{ route('saveWebSiteElement') }}',
                            data: {
                                id: id,
                                action: action,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status) {
                                    successMessage(response.message,true);
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
                    url: '{{ route('saveWebSiteElement') }}',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if(response.status){
                            successMessage(response.message,"reload");
                        }else{
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
