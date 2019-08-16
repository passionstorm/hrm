@extends('layout.index')

@section('css')
  <style> 
    @media only screen and (min-width:992px){
      .r-w-o1{
        width: 25%;
      }
    }
  </style>
@endsection

@section('content')
<div class="content-wrapper">
  {{-- <button id="test">test</button> --}}
  <section class="content-header">
      <h3><span>Vacation</span></h3>
  </section>
  <section class="content">
    <div class="box box-primary">
        <div style="padding: 10px 20px">
          <div class="row">
            <div class="box box-info" style="border: none">
              <div class="box-header with-border">
                  <span style="font-weight:bold">Pendding</span>
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <table class="table no-margin">
                    <thead>
                      <tr>
                        <th class="r-w-o1" >Start</th>
                        <th class="r-w-o1">End</th>
                        <th>Spent</th>
                        <th>Type</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($pendding as $i)
                          <tr>
                            <td>{{$i->start}}</td>
                            <td>{{$i->end}}</td>
                            <td>{{$i->spent}} minutes</td>
                            <td>
                              @foreach (Constants::VACATION_TYPE as $key => $item)
                                @if($i->type == $key)
                                  {{$item}}
                                @endif
                              @endforeach
                            </td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </section>
</div>
@endsection

@section('script')
@endsection