@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <h1 class="page-header">sakeEvent - {{config('sake.area_jp')[$area ?? '']}}</h1>
        <div>
          <ul>
            <li><a href="http://nihonshucalendar.com/">日本酒カレンダー</a>のUIがいろいろキツイので突貫で作った。1時間に1回データ同期してます。</li>
            <li>特に許可とかとってないんで怒られたらやめます。</li>
            <li>クラフトビールの情報取得先募集中。</li>
            <li>苦情・要望は<a href="https://twitter.com/achel_b8">@achel_b8</a>まで。</li>
          </ul>
        </div>
      </div>
    </div>
    <!-- event -->
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">
            <!-- search -->
            <form>
              <div class="row">
                <div class="col-sm-4">
                  <label class="sr-only" for="date">date</label>
                  <div class="input-group @if($errors->first('date')) has-error @endif">
                    <div class="input-group-addon"><i class="fa fa-calendar-o"></i></div>
                    <input type="date" id="date" name="date" class="form-control"
                           value="{{old('date', $date)}}">
                  </div>
                </div>
                <div class="col-sm-8">
                  <label class="sr-only" for="select">select</label>
                  <select id="select" name="type">
                    <option value="all" @if(is_null($type) || $type === 'all' ) selected @endif>すべて</option>
                    <option value="sake" @if($type === 'sake') selected @endif>日本酒</option>
                    <option value="beer" @if($type === 'beer') selected @endif>クラフトビール</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <!-- list -->
          <div class="panel-body">
            <div class="infinite-scroll">
              @foreach($events as $event)
                <h3 class="text-danger">{{$event->date}}</h3>
                <span class="label label-danger">{{$event->prefecture->name}}</span>
                <span class="label {{$event->color}}">{{$event->type}}</span>
                @if($event->is_recommended)
                  <span class="label label-warning">オススメ</span>
                @endif
                <h4><a href="{{$event->url}}">{{$event->summary}}</a>

                </h4>
                <h4>
                  <i class="fa fa-fw fa-map-marker"></i>
                  {{$event->location}}
                  <a class="btn btn-sm btn-info"
                     href="https://maps.google.co.jp/maps/search/{{$event->location}}">Map</a>
                </h4>
                <p>
                  {{$event->shortDescription}}
                </p>
                <hr>
              @endforeach
              {{$events->appends(['date' => $date, 'type' => $type])->links()}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('styles')
<link href="{{ asset('vendor/quick-select/dist/css/quickselect.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/2.1.0/jquery.infinitescroll.min.js"></script>
<script>
  $('.infinite-scroll').infinitescroll({
    loading: {
      finished: function () {
        //load bar
        $('#infscr-loading').remove();
        $('.loading').remove();
        //laravel pagination
        $('ul.pagination').hide();
      },
      finishedMsg: '<div class="end-msg">Congratulations!</div>',
      msgText: '<div class="loading">Loading...</div>'
    },
    navSelector: '.pagination',
    nextSelector: '.pagination li.active + li a',
    itemSelector: 'div.infinite-scroll'
  });
</script>

<script src="{{asset('vendor/quick-select/dist/js/jquery.quickselect.min.js')}}"></script>
<script>
  $('#select').quickselect({
    activeButtonClass: 'btn-primary active',
    buttonClass: 'btn btn-default',
    breakOutAll: true
  });
</script>

<script>
  (function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
          (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
    a = s.createElement(o),
        m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
  })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

  ga('create', 'UA-104084422-1', 'auto');
  ga('send', 'pageview');
</script>
@endpush