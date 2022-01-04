@extends('layouts.app')
@section('content')

@include('layouts.top-header', [
        'title' => __('Booking'),
        'class' => 'col-lg-7'
    ])

<div class="container-fluid mt--6">
    <div class="row mb-5">
        <div class="col">
            <div class="card pb-4">
                <!-- Card header -->
                <div class="card-header border-0">
                    <span class="h3">{{__('Booking table')}}</span>
                    <div class="">
                        <button class="btn btn-primary addbtn float-right p-2 add_appointment" id="add_appointment"><i class="fas fa-plus mr-1"></i>{{__('Add Appointment')}}</button>
                    </div>
                </div>
                <!-- table -->
                <div class="table-responsive">

                    <table class="table align-items-center table-flush" id="booking_dt" class="display">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort">{{__('#')}}</th>
                                <th scope="col" class="sort">{{__('Booking id')}}</th>
                                <th scope="col" class="sort">{{__('User Name')}}</th>
                                {{-- <th scope="col" class="sort">{{__('Services')}}</th> --}}
                                <th scope="col" class="sort">{{__('Date')}}</th>
                                <th scope="col" class="sort">{{__('Emp')}}</th>
                                <th scope="col" class="sort">{{__('Duration')}}</th>
                                {{-- <th scope="col" class="sort">{{__('Payment')}}</th> --}}
                                {{-- <th scope="col" class="sort">{{__('Paid')}}</th> --}}
                                <th scope="col" class="sort">{{__('Booking Status')}}</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody class="list">
                            @if (count($bookings) != 0)
                                @foreach ($bookings as $key => $booking)
                                    <tr>
                                        
                                        <th>{{$bookings->firstItem() + $key}}</th>
                                        <td>{{$booking->booking_id}}</td>
                                        <td>{{$booking->userDetails['full_name']}}</td>
                                        
                                        <td>{{$booking->date}}</td>
                                        <td>{{$booking->empDetails->user->full_name}}</td>
                                        <td>{{$booking->start_time}} To {{$booking->end_time}}</td>
                                        
                                        <td>
                                            <select class="form-control" onchange="changeStatus({{$booking->id}})" name="selector" id="selector{{$booking->id}}" {{$booking->booking_status == "Completed" || $booking->booking_status == "Cancel"?'disabled': ''}} >
                                                <option value="Pending" {{$booking->booking_status == "Pending"?'selected': ''}} disabled>Pending</option>
                                                <option value="Cancel" {{$booking->booking_status == "Cancel"?'selected': ''}}>Cancel</option>
                                                <option value="Approved" {{$booking->booking_status == "Approved"?'selected': ''}}>Approved</option>
                                                <option value="Completed" {{$booking->booking_status == "Completed"?'selected': ''}}>Completed</option>
                                            </select>
                                        </td>
                                        <td class="table-actions">
                                            @php
                                                $base_url = url('/');
                                            @endphp
                                            <button class="btn-white btn shadow-none p-0 m-0 table-action text-warning bg-white" onclick="show_booking({{$booking->id}},'{{$base_url}}','booking')" data-toggle="tooltip" data-original-title="{{__('View Appointment')}}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th colspan="15" class="text-center">{{__('No Bookings')}}</th>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>
                    <div class="float-right mr-4 mt-3">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.booking.create')
@include('admin.booking.show')

@endsection