@extends('products.layout')

@section('content')
   
<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="float-left">
      <h2>
        Edit Product
      </h2>
    </div>
    <div class="float-right">
    <a href="{{route('products.index')}}" class="btn btn-primary">back</a>
    </div>
  </div>

@if ($errors->any())
<div class="alert alert-danger">
  <strong>Whoops</strong> There is some error with your input <br><br>
  <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
  </ul>
</div>
    
@endif

<form action="{{route('products.update',$product->id)}}" method="post">
  @csrf
  @method('PUT')

  <div class="row">
    <div class="col-xs12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Name</strong>
        <input type="text" name="name" class="form-control" placeholder="Name">  
      </div>
      <div class="form-group">
        <strong>Detail</strong>
        <textarea class="form-control" name="detail" style="height:150px" placeholder="Detail"></textarea>
      </div>


    </div>
    <div class="col-xs12 col-sm-12 col-md-12 text-center">
      <button type="submit" class="btn btn-primary" >
        Submit
      </button>
    </div>
  </div>

</form>

@endsection