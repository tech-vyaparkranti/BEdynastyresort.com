@extends('layouts.dashboardLayout')
@section('title', 'Manage FAQ')
@section('content')
    <x-dashboard-container container_header="Manage FAQ">
        <x-card>
            <x-card-header>Add FAQ</x-card-header>
            <x-card-body>
                <x-form-element method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                    <x-input type="hidden" title="id" id="id" value="" name="id"></x-input>
                    <x-input type="hidden" title="action" id="action" value="insert" name="action"></x-input>

                    <x-content-div heading="Add FAQ">
                        <div id="itinerarySection">
                            {{-- Loop and create blocks for questions and answers --}}
                            <div class="row itinerary-block" id="itinerary-block-">
                                <x-input-with-label-element required type="text" name="questions[]"
                                    placeholder="Ask Questions" label="Question"></x-input-with-label-element>
                                <x-text-area-with-label  required name="answers[]"
                                    placeholder="Write Answer" label="Write Answer"></x-text-area-with-label>
                                <div class="col-md-2 col-sm-12 text-center">
                                    <x-button type="button" onclick="addNewItineraryBlock()"
                                        class="btn btn-primary btn-icon mt-4">+</x-button>
                                </div>
                            </div>
                        </div>
                    </x-content-div>

                    <x-form-buttons></x-form-buttons>
                </x-form-element>
            </x-card-body>
        </x-card>

        <x-card>
            <x-card-header>Our FAQ Data</x-card-header>
            <x-card-body>
                <x-data-table></x-data-table>
            </x-card-body>
        </x-card>
    </x-dashboard-container>

    @section('script')
    @include('Dashboard.include.dataTablesScript')
    <script type="text/javascript">
        let table; 

        $(function() {
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('getFaq') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}" // CSRF token is included here
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", xhr.responseText);
                        alert("Error loading data. Check the console for details.");
                    }
                },
                columns: [
                    { data: "DT_RowIndex", orderable: false, searchable: false, title: "Sr. No." },
                    { data: 'questions', name: 'questions', orderable: false, searchable: false, title: 'Questions' },
                    { data: 'answers', name: 'answers', orderable: false, searchable: false, title: 'Answers' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, title: 'Actions' }
                ],
                order: [[1, "desc"]]
            });
        });

        function addNewItineraryBlock() {
            const blockId = `itinerary-block-${Date.now()}`;
            const newBlock = `
                <div class="row itinerary-block" id="${blockId}">
                    <x-input-with-label-element type="text" name="questions[]" placeholder="Question" label="Question"></x-input-with-label-element>
                    <x-text-area-with-label name="answers[]" placeholder="Write Answer" label="Write Answer"></x-text-area-with-label>
                    <div class="col-md-2 col-sm-12 text-center">
                        <x-button type="button" onclick="removeBlock('${blockId}')" class="btn btn-danger btn-icon mt-4">-</x-button>
                    </div>
                </div>`;
            document.getElementById('itinerarySection').insertAdjacentHTML('beforeend', newBlock);
        }

        function removeBlock(blockId) {
            document.getElementById(blockId).remove();
        }

        $("#submitForm").on("submit", function() {
            var form = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '{{ route('saveFaq') }}',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        successMessage(response.message, true);
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

        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#action").val("update");
                $("button[onclick='addNewItineraryBlock()']").hide();
                $("#id").val(row['id']);
                $("input[name='questions[]']").val(row['questions']); // Directly set the value for question
                $("textarea[name='answers[]']").val(row['answers']);
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
                            url: '{{ route('saveFaq')}}',
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

    @endsection
@endsection
