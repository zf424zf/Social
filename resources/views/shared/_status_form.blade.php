<form action="{{ route('status.store') }}" method="POST">
    @include('shared.error')
    {{ csrf_field() }}
    <textarea class="form-control" rows="3" placeholder="聊聊新鲜事儿..." name="content">{{ old('content') }}</textarea>
    <button type="submit" class="btn btn-success pull-right">发布</button>
</form>