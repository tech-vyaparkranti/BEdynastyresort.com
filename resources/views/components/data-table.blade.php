<div class="card-body mt-2 table-responsive-lg table-responsive">
    <table  class="{{ $attributes["table_class"]??" table table-bordered data-table table-responsive cell-border"}}" id="{{$attributes["id"]??"data_table_id"}}" >
        {{$slot}}
    </table>
</div>