@extends('products.layout')
 
@section('content')
    <div class="row" style="margin-top: 30px;">
        <div class="col-lg-12 margin-tb" >
            <div class="pull-left">
                <h2>Products list</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>
            </div>
        </div>
    </div>
   {{ app()->version() }}
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Price</th>
            <th>Categories</th>
            <th>Image</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($products as $product)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $product->name }}</td>
            <td>
            {!! substr_replace( $product->price, '.', (strlen($product->price) - 3), -2) !!} $</td>
            <td>
                @foreach ($product_categories as $productCat)
                    
                    @if ($product->category_id == $productCat->id)
                        {{ $productCat->name }}
                    @endif
                
                @endforeach
            </td>
            <td>
                
                        {{ $productCat->image }}
                    
            </td>
            <td>
                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
   
                    <a class="btn btn-info" href="{{ route('products.show',$product->id) }}">Show</a>
    
                    <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
   
                    @csrf
                    @method('DELETE')
      
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
  
    {!! $products->links() !!}
      
@endsection