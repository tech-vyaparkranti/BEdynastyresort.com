@extends('layouts.dashboardLayout')
@section('title', 'Manage Offer')
@section('content')

    <x-dashboard-container container_header="Manage Offer">
        <x-card>
            <x-card-header>Add Offer Items</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                    <x-input-with-label-element type="text" name="title" id="title" placeholder=""
                        label="Title" required="true"></x-input-with-label-element>

                    <x-input-with-label-element type="file" name="image" id="image" placeholder=""
                        label="Image" accept="image/*"></x-input-with-label-element>
                    
                    <div id="image_preview" class="mt-2 mb-3" style="display: none;">
                        <img id="preview_img" src="" alt="Preview" style="max-width: 200px; max-height: 200px;" />
                    </div>
                    
                    <x-input-with-label-element type="number" name="price" id="price" placeholder=""
                        label="Price" required="true" step="0.01"></x-input-with-label-element>
                        
                    <x-input-with-label-element type="number" name="offer_price" id="offer_price" placeholder=""
                        label="Offer Price" step="0.01"></x-input-with-label-element>
                    <x-select-label-group required name="status" id="view_status" label_text="View Status">
                            <option value="1">Visibile</option>
                            <option value="0">Hidden</option>
                    </x-select-label-group>
                    <div class="form-group">
                        <label for="features">Features</label>
                        <div id="features-container">
                            <div class="input-group mb-2 feature-row">
                                <input type="text" class="form-control feature-input" name="features[]" placeholder="Enter feature">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-feature" style="display: none;">Remove</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="add-feature">Add Feature</button>
                        <input type="hidden" name="features_json" id="features_json" value="[]">
                    </div>

                    
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-header>Offer Data</x-card-header>
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
                    url: "{{ route('offerData') }}",
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
                        data: 'price',
                        name: 'price',
                        title: "Price"
                    },
                    {
                        data: 'offer_price',
                        name: 'offer_price',
                        title: "Offer Price"
                    },
                    {
                        data: 'features',
                        name: 'features',
                        title: "Features"
                    },
                    {
                        data: 'image',
                        name: 'image',
                        title: "Image",
                        render: function(data) {
                            return data ? '<img src="' +  data + '" height="50" />' : 'No Image';
                        }
                    },
                    
                ]
            });

        });
        
        // Image preview functionality
        $(document).on('change', '#image', function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_img').attr('src', e.target.result);
                    $('#image_preview').show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#image_preview').hide();
            }
        });
        
        // Features functionality
        $(document).ready(function() {
            // Add new feature field
            $('#add-feature').click(function() {
                let newFeatureRow = `
                    <div class="input-group mb-2 feature-row">
                        <input type="text" class="form-control feature-input" name="features[]" placeholder="Enter feature">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-feature">Remove</button>
                        </div>
                    </div>
                `;
                $('#features-container').append(newFeatureRow);
                updateFeaturesJson();
            });
            
            // Remove feature field
            $(document).on('click', '.remove-feature', function() {
                $(this).closest('.feature-row').remove();
                updateFeaturesJson();
            });
            
            // Update features when input changes
            $(document).on('input', '.feature-input', function() {
                updateFeaturesJson();
            });
            
            // Function to update hidden JSON field
            function updateFeaturesJson() {
                let features = [];
                $('.feature-input').each(function() {
                    if($(this).val().trim() !== '') {
                        features.push($(this).val());
                    }
                });
                $('#features_json').val(JSON.stringify(features));
            }
        });

        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#title").val(row['title']);
                $("#view_status").val(row['status']);
                $("#price").val(row['price']);
                $("#offer_price").val(row['offer_price']);
                $("#action").val("update");
                
                // Clear existing feature fields except the first one
                $('.feature-row:not(:first)').remove();
                $('.feature-input').val('');
                
                // If features exist, populate the fields
                if (row['features']) {
                    let features = [];
                    try {
                        features = JSON.parse(row['features']);
                    } catch(e) {
                        console.error("Error parsing features JSON");
                    }
                    
                    if (features.length > 0) {
                        // Set first feature
                        $('.feature-input:first').val(features[0]);
                        
                        // Add fields for remaining features
                        for (let i = 1; i < features.length; i++) {
                            $('#add-feature').click();
                            $('.feature-input:last').val(features[i]);
                        }
                    }
                    
                    // Update the hidden JSON field
                    $('#features_json').val(row['features']);
                }
                
                // Show image preview if it exists
                if (row['image']) {
                    $('#preview_img').attr('src',  row['image']);
                    $('#image_preview').show();
                } else {
                    $('#image_preview').hide();
                }
                
                scrollToDiv();
            }
        });
        
        $(document).ready(function() {
            $("#submit_form").on("submit", function(e) {
                e.preventDefault();
                
                // Update the features JSON before submission
                updateFeaturesJson();
                
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('saveOffer') }}',
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
            
            // Helper function to update features JSON
            function updateFeaturesJson() {
                let features = [];
                $('.feature-input').each(function() {
                    if($(this).val().trim() !== '') {
                        features.push($(this).val());
                    }
                });
                $('#features_json').val(JSON.stringify(features));
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
                            url: '{{ route('saveOffer') }}',
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