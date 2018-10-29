<form action="{{route('store.category')}}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    Category name: <input type="text" name="name">
    <button>TIZ</button>
</form>