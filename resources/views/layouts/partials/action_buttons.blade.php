<div class="action-buttons-group">
    @if(isset($showView) ? $showView : true)
        @if(isset($viewRoute))
            <a href="{{ $viewRoute }}" class="btn-action btn-action-view" title="View" data-bs-toggle="tooltip">
                <i class="fa fa-eye"></i>
            </a>
        @endif
    @endif

    @if(isset($showEdit) ? $showEdit : true)
        @if(isset($editRoute))
            <a href="{{ $editRoute }}" class="btn-action btn-action-edit" title="Edit" data-bs-toggle="tooltip">
                <i class="fa fa-pencil"></i>
            </a>
        @endif
    @endif

    {!! $extraButtons ?? '' !!}

    @if(isset($showDelete) ? $showDelete : true)
        @if(isset($deleteRoute))
            <form action="{{ $deleteRoute }}" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('{{ $deleteConfirm ?? 'Are you sure you want to delete this?' }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action btn-action-delete" title="Delete" data-bs-toggle="tooltip">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        @endif
    @endif
</div>
