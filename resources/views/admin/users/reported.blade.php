@extends('layouts.app')
@section('content')

@include('layouts.top-header', [
    'title' => __('View') ,
    'headerData' => __('User') ,
    'url' => 'fetchreported' ,
    'class' => 'col-lg-7'
])

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12 order-xl-2 mb-5 mb-xl-0">
            <div class="card card-profile shadow">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-body pt-0 pt-md-12">
                            <div class="row">
                                <div class="col">
                                    <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                        
                                        <div>
                                            {{-- <span class="heading">{{$patient->full_name}}</span> --}}
                                            <span class="description">{{__('Patients')}}</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                
                                <hr class="my-4" />
                                @foreach ($patients as $patient)
                                    
                                    <div class="text-left">{{$patient->email}}</div>
                                    <a href={{url('remove/'.$patient->id)}}>Remove user</a>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="meeting"></div>
{{-- <script src='https://meet.jit.si/external_api.js'></script> --}}
<script type="text/javascript">
    const domain = 'meet.jit.si/';
    const options = {
    roomName: 'halloaddisababa',
    width: "100%",
    height: 920,
    parentNode: document.querySelector('#meeting'),
    onload: function(){
    }
    };
    const api = new JitsiMeetExternalAPI(domain, options);
</script>

@endsection