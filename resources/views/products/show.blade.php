@extends('products.layout')

@section('content')
 
<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="float-left">
      <h2>Show product</h2>
    </div>
    <div class="float-right">
      <a href="{{route('products.index')}}" class="btn btn-primary">back</a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <strong>Name</strong>
      {{$product->name}}
    </div>
  </div>
  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <strong>Details</strong>
      {{$product->detail}}
    </div>
     <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <strong>Updated_at</strong>
      {{$product->updated_at}}
    </div>
  </div>
</div>

@endsection