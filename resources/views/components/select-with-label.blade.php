<div class="{{ $attributes["div_class"]??"col-md-4 col-sm-12 mb-3" }}">
    <label class="form-label" for="{{$attributes["id"]??""}}">{{$attributes["label"]??""}}</label>
    @if(!empty($attributes["required"]))
        <span class="text-danger">*</span>
    @endif
    <div class="input-group input-group-merge">
        <select {{$attributes->merge(["class"=>"form-control"])}}   >
            <option value="">Select</option>
            {{$slot}}
        </select>
    </div>
</div>