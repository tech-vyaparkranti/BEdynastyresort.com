@extends('layouts.dashboardLayout')
@section('title', 'Edit Destination')
@section('content')
    <x-dashboard-container container_header="Manage Destination">
        <x-card>
            <x-card-header>Edit Destination</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value="{{ $packageData->id }}"></x-input>
                    <x-input type="hidden" name="action" id="action" value="edit"></x-input>

                    <x-input-with-label-element required name="package_name" id="package_name" placeholder=" Name"
                        label=" Name"
                        value="{{ old('package_name', $packageData->package_name) }}"></x-input-with-label-element>

                    {{-- <x-input-with-label-element name="old_image" id="old_image" type="image" src="{{ asset($packageData->package_image) }}"
                        label="Old Image"  ></x-input-with-label-element> --}}
                    <x-input-with-label-element name="old_image" id="old_image" type="image"
                        src="{{ isset($packageData->package_image[0]) ? asset('storage/' . $packageData->package_image[0]) : '' }}"
                        label="Old Image">
                    </x-input-with-label-element>


                    <x-text-area-with-label id="description" label=" Description"
                        name="description"> {{ old('description', $packageData->description) }}</x-text-area-with-label>
                    <x-input-with-label-element name="package_image[]" id="package_image" type="file"
                        label="Upload Image" placeholder="Images" accept="image/*"
                        multiple></x-input-with-label-element>

                    
                     <x-input-with-label-element name="package_country" id="package_country" placeholder="Location"
                        label="Location"  value="{{ old('package_country', $packageData->package_country) }}"></x-input-with-label-element>

                        <x-input-with-label-element id="meta_keyword" label="Meta Keyword"
                        name="meta_keyword" value="{{ old('meta_keyword', $packageData->meta_keyword) }}"></x-input-with-label-element>
                        <x-input-with-label-element id="meta_title" label="Meta Title"
                        name="meta_title" value="{{ old('meta_title', $packageData->meta_title) }}"></x-input-with-label-element>
                        <x-input-with-label-element id="meta_description" label="Meta Description"
                        name="meta_description" value="{{ old('meta_description', $packageData->meta_description) }}"></x-input-with-label-element>
                    
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>

    </x-dashboard-container>
    
@endsection

@section('script')

    @include('Dashboard.include.dataTablesScript')
    <script type="text/javascript">
      $('#description').summernote({
            placeholder: 'Description',
            tabsize: 2,
            height: 100
        });
        // $('#description').summernote({
        //     placeholder: 'Description',
        //     tabsize: 2,
        //     height: 100
        // });
        // let itinerary = $.parseJSON(`{!! json_encode($packageData->itinerary) !!}`);
        // let days_old = $.parseJSON(`{!! json_encode(old('days', [])) !!}`);
        // let city_id_old = $.parseJSON(`{!! json_encode(old('city_id', [])) !!}`);
        // $(document).ready(function() {
        //     if (days_old.length) {
        //         let element = 0;
        //         days_old.forEach(day => {
        //             if (element == 0) {
        //                 $("#days").val(day);
        //                 $("#city_id").val(city_id_old[0]).select2();
        //             } else {
        //                 insertNewItinary(day, city_id_old[element]);
        //             }
        //             element++;
        //         });
        //     } else {
        //         if (itinerary.length) {
        //             let number = 0;
        //             itinerary.forEach(element => {
        //                 if (number == 0) {
        //                     $("#days").val(element.days);
        //                     $("#city_id").val(element.city_id);
        //                 } else {
        //                     insertNewItinary(element.days, element.city_id);
        //                 }
        //                 number++;
        //             });
        //         }
        //     }
        // });

       
        $(document).ready(function() {
            $("#submit_form").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('packageMaster.store') }}',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            successMessage(response.message, false,
                                '{{ route('packageMaster.index') }}');

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

@endsection
