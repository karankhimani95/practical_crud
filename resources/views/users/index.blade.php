@extends('layouts.app')

@section('content')

    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
            @endif
            <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <td class="col">Number</td>
                    <td class="col">Actions</td>
                  </tr>
                </thead>
                <tbody>
                    @foreach($users  as $user)
                        <tr>
                            <th scope="row">{{$user->id}}</th>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->mobile_no}}</td>
                            @if($user->user_type == config('app.user_type.user') && auth()->user()->id == $user->id)
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}"
                                class="btn btn-primary btn-sm">Edit</a>
                            </td>
                            @elseif(auth()->user()->user_type == config('app.user_type.admin'))
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}"
                                class="btn btn-primary btn-sm">Edit</a>
                            </td>
                            @endif
                            @if(auth()->user()->user_type == config('app.user_type.admin'))
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                              </form>
                            </td>
                            @endif
                        </tr>
                    @endforeach 
                </tbody>
              </table>
        </div>
    </div>
</div>
@endsection
