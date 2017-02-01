@if(!empty($item->gallery))
    <?php $galleryTitles = $item->getGalleryTitles() ?>
    @foreach($item->getGallery() as $index=>$image)
        <div class="items" id="i{{$index}}">
            @if(!empty($canUpdate))
            <div class="control">
                <div class="bg"></div>
                <div class="edit">
                    <input name="galleryTitles[]" value="{{ $galleryTitles[$index] or '' }}" type="text" /> <a href="javascript:void(0);" onclick="return saveGalleryTitles();" class="fa fa-save"></a>
                </div>
                <a href="{{ route('admin::act', ['action'=>'imagedrop', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>(!empty($item->id) ? $item->id : 0)], false) }}" onclick="return imagedrop(this, {{ $index }});" class="el fa fa-trash-o"></a>
                <a href="javascript:void(0);" onclick="return showGalleryImageEditField(this, {{ $index }});" class="el fa fa-pencil"></a>
            </div>
            @endif
            @if(empty($item->id))
                <img src="/images/tmp/thumbs/{{ $image }}.jpg" />
            @else
                <img src="/images/thumbs/{{ $image }}.jpg" />
            @endif
        </div>
    @endforeach
@endif