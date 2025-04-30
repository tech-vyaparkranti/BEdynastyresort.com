  <div class="modal fade" id="{{ $attributes["id"]??'' }}" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
    <div {!! $attributes->merge(["class"=>"modal-dialog"]) !!}>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="backDropModalTitle">{!! $attributes['modal_title']??'' !!}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{ $slot }}
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
          </button>
          <button type="button" class="btn btn-primary">Save</button> --}}
        </div>
    </div>
    </div>
  </div>