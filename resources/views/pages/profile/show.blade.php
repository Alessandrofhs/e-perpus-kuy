@extends('layouts.main')
@section('title', 'Profile')

@section('content')

<div class="pc-content">

    <!-- Breadcrumb -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">

                <div class="col-md-12">

                    <div class="page-header-title">
                        <h5 class="m-b-10">My Profile</h5>
                    </div>

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            Profile
                        </li>

                        <li class="breadcrumb-item active">
                            My Profile
                        </li>
                    </ul>

                </div>

            </div>
        </div>
    </div>

    <!-- Profile -->
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <!-- Photo -->
                    <div class="text-center mb-5">

                        <img 
                            src="{{ $user->photo 
                                    ? asset('storage/' . $user->photo) 
                                    : asset('templates/dist/assets/images/user/avatar-2.jpg') }}"
                            alt="profile-photo"
                            class="rounded-circle shadow"
                            style="
                                width: 150px;
                                height: 150px;
                                object-fit: cover;
                            "
                        >

                        <h4 class="mt-3 mb-1">
                            {{ $user->name }}
                        </h4>

                        <span class="badge bg-primary">
                            {{ ucfirst($user->role) }}
                        </span>

                    </div>

                    <!-- Name -->
                    <div class="row mb-3 align-items-center">

                        <label class="col-md-2 fw-bold">
                            Name
                        </label>

                        <div class="col-md-10">
                            <input 
                                type="text"
                                class="form-control"
                                value="{{ $user->name }}"
                                readonly
                            >
                        </div>

                    </div>

                    <!-- Email -->
                    <div class="row mb-3 align-items-center">

                        <label class="col-md-2 fw-bold">
                            Email
                        </label>

                        <div class="col-md-10">
                            <input 
                                type="email"
                                class="form-control"
                                value="{{ $user->email }}"
                                readonly
                            >
                        </div>

                    </div>

                    <!-- Major -->
                    <div class="row mb-3 align-items-center">

                        <label class="col-md-2 fw-bold">
                            Major
                        </label>

                        <div class="col-md-10">
                            <input 
                                type="text"
                                class="form-control"
                                value="{{ $user->major }}"
                                readonly
                            >
                        </div>

                    </div>

                    <!-- Role -->
                    <div class="row mb-3 align-items-center">

                        <label class="col-md-2 fw-bold">
                            Role
                        </label>

                        <div class="col-md-10">
                            <input 
                                type="text"
                                class="form-control"
                                value="{{ ucfirst($user->role) }}"
                                readonly
                            >
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection