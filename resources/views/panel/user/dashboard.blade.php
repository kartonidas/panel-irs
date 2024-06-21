@extends("panel.layout")
  
@section("title"){{ __("Tablica") }}@endsection

@section("content")
    Tablica po zalogowaniu
    
    <div>
        <a href="{{ route("profile") }}">{{ __("Mój profil") }}</a>
    </div>
@endsection