@extends('layouts.app')

@section('styles')
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('/css/bootstrap-select.min.css') !!}
@endsection

@section('title-bar')
    @include('partials._titlebar')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="section-details">
                    <h2>Welcome</h2>
                    <p>To book with us, it’s quick and easy, just fill in the details and submit your booking.  You’ll get confirmation via email straight away, along with instructions as to how you or anyone else in your group can add or amend the information, at any time.  Please check your JUNK and SPAM folders. Then you can leave the rest to us.<p>
                    <p><strong>Remember, there is nothing to pay until you arrive in resort and no payment details are requested.</strong></p>
                    <p>Should you have any problems please email us at <a href="mailto:info@skihire2u.com" target="_blank">info@skihire2u.com</a> or call us on <a href="tel:+33450721970" value="+33450721970" target="_blank">+33 4 50 72 19 70</a></p>
                </div>
                <hr>
                <div class="new-boooking-wrapper clearfix">
                    {!! Form::open(array('route' => 'dateDetails', 'method' => 'POST', 'data-parsley-validate' => '')) !!}
                        <div class="form-section">
                            <h3>Accommodation Details</h3>
                            <p>Please select your accommodation or "Not Listed – Independent" if not found</p>
                            <div class="form-group">
                                {{ Form::label('chalet_id', 'Chalet') }}
                                <select id="chalet_id" name="chalet_id" class="col-md-12 custom-select selectpicker" data-show-subtext="true" data-live-search="true">
                                    @foreach ($operators as $operator)
                                    @if ($operator->is_active == 1)
                                    @foreach ($operator->accommodations as $acc)
                                    @if ($acc->is_active == 1)
                                    <option value="{{ $acc->id }}" data-subtext="{{ $acc->operator->name }}" {{ $acc->id == '1' ? 'selected="selected"' : '' }}>{{ $acc->name }}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div id="independent-info" class="hidden">
	                            <div class="form-group">
	                                {{ Form::label('chalet_name', 'Name of Accommodation') }}
	                                {{ Form::text('chalet_name', null, array('id' => 'chalet-name', 'class' => 'form-control', 'required' => '')) }}
	                            </div>
	                            <div class="form-group">
	                                {{ Form::label('chalet_address', 'Address') }}
	                                {{ Form::text('chalet_address', null, array('id' => 'chalet-address', 'class' => 'form-control', 'required' => '')) }}
	                            </div>
	                        </div>
                        </div>
                        <hr>
                        <div class="form-section">
                            <h3>Dates</h3>
                            <div class="form-group">
                                {{ Form::label('arrival_dtp', 'Arrival Date/Time') }}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class='input-group date' id='arrival_dtp'>
                                            <input name="arrival_dtp" type='text' class="form-control" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class='input-group date' id='arrival_time'>
                                            <input name="arrival_time" type='text' class="form-control" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('departure_dtp', 'Departure Date/Time') }}
                                <div class='input-group date' id='departure_dtp'>
                                    <input name="departure_dtp" type='text' class="form-control" required />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('mountain_dtp', 'First day on Mountain') }}
                                <div class='input-group date' id='mountain_dtp'>
                                    <input name="mountain_dtp" type='text' class="form-control" required />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('duration', 'Duration') }}
                                {{ Form::text('duration', null, array('id' => 'duration', 'class' => 'form-control', 'readonly' => '')) }}
                            </div>
                        </div>
                        <hr>
                        {{ Form::submit('Continue', array('class' => 'btn btn-success')) }}

                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-md-4 col-md-offset-1">
                @include('partials._sidebar')
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    {!! Html::script('js/bootstrap-select.min.js') !!}
    {!! Html::script('js/moment-with-locales.js') !!}
    {!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
    <script type="text/javascript">
        jQuery(document).ready(function() {
           	checkChalet();

           	jQuery('#chalet_id').change(function() {
           		checkChalet();
           	});

            jQuery('#arrival_dtp').datetimepicker({
                format: 'YYYY-MM-DD',
                showClear: true,
                debug: true,
            });

            jQuery('#arrival_time').datetimepicker({
                format: 'HH:mm',
                debug: true,
            });

            /*

            jQuery('#arrival_dtp').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                stepping: 30,
                minDate: moment().endOf('hour'),
                showClear: true,
                sideBySide: true
            });
            jQuery('#departure_dtp').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                stepping: 30,
                minDate: moment().endOf('hour'),
                useCurrent: false,
                sideBySide: true
            });
            jQuery('#mountain_dtp').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                defaultDate: moment().add(1, 'day').hour(9).minute(0).second(0),
                stepping: 30,
                minDate: moment().endOf('hour'),
                useCurrent: false,
                sideBySide: true
            });

            var update = false;
            var update2 = true;

            jQuery("#arrival_dtp").on("dp.show", function (e) {
                update2 = true;
            });
            jQuery("#arrival_dtp").on("dp.change", function (e) {
                var date = moment().set({
                    'year': moment(e.date).get('year'),
                    'month': moment(e.date).get('month'),
                    'date': moment(e.date).get('date'),
                    'hour': 0,
                    'minute': 0,
                    'second': 0,
                });
                var date1 = moment().set({
                    'year': moment(e.date).get('year'),
                    'month': moment(e.date).get('month'),
                    'date': moment(e.date).add(1, 'day').get('date'),
                    'hour': 9,
                    'minute': 0,
                    'second': 0,
                });
                var date2 = moment().set({
                    'year': moment(e.date).get('year'),
                    'month': moment(e.date).get('month'),
                    'date': moment(e.date).add(2, 'day').get('date'),
                    'hour': 9,
                    'minute': 0,
                    'second': 0,
                });
                jQuery('#departure_dtp').data("DateTimePicker").minDate(date);
                if(update2) {
                    alert(date1);
                    update = false;
                    jQuery('#mountain_dtp').data("DateTimePicker").date(date1);
                    jQuery('#mountain_dtp').data("DateTimePicker").minDate(date);
                }

                calculateDuration();
                
                /*
                var mtn = jQuery('#mountain_dtp').data("DateTimePicker").date();
                if(mtn) {
                    jQuery('#departure_dtp').data("DateTimePicker").minDate(mtn);
                } else {
                    jQuery('#departure_dtp').data("DateTimePicker").minDate(e.date);
                }
                jQuery('#mountain_dtp').data("DateTimePicker").minDate(date);
                
            });

            jQuery("#departure_dtp").on("dp.change", function (e) {
                var mtn = jQuery('#mountain_dtp').data("DateTimePicker").date();
                var date = moment().set({
                    'year': moment(e.date).get('year'),
                    'month': moment(e.date).get('month'),
                    'date': moment(e.date).get('date'),
                    'hour': 0,
                    'minute': 0,
                    'second': 0,
                });
                if(mtn) {
                    jQuery('#arrival_dtp').data("DateTimePicker").maxDate(mtn);
                } else {
                    jQuery('#arrival_dtp').data("DateTimePicker").maxDate(date);
                }
                jQuery('#mountain_dtp').data("DateTimePicker").maxDate(date);

                calculateDuration();

            });
            jQuery("#mountain_dtp").on("dp.show", function (e) {
                update = true;
            });
            jQuery("#mountain_dtp").on("dp.change", function (e) {
                if(update) {
                    update2 = false;
                    var date = moment().set({
                        'year': moment(e.date).get('year'),
                        'month': moment(e.date).get('month'),
                        'date': moment(e.date).get('date'),
                        'hour': moment(e.date).get('hour'),
                        'minute': moment(e.date).get('minute'),
                        'second': 0,
                    });
                    jQuery('#arrival_dtp').data("DateTimePicker").date(e.date);
                    jQuery('#arrival_dtp').data("DateTimePicker").maxDate(e.date);
                    jQuery('#departure_dtp').data("DateTimePicker").minDate(e.date);

                    calculateDuration();

                }
                
                //jQuery('#departure_dtp').data("DateTimePicker").minDate(e.date);
            });
*/

        });

        function calculateDuration() {
            var a = jQuery('#mountain_dtp').data("DateTimePicker").date();
            var b = jQuery('#departure_dtp').data("DateTimePicker").date();
            if(a && b) {
                dur = b.diff(a, 'days')+1;
                jQuery('#duration').val(dur + ' day(s)');
            }
        }

		function checkChalet() {
	        var val = jQuery('#chalet_id').val();
	        if( val == '1') {
	            jQuery('#independent-info').removeClass('hidden');
	            jQuery('#chalet-name').val('');
	            jQuery('#chalet-address').val('');
	        } else {
	            jQuery('#independent-info').addClass('hidden');
	            jQuery('#chalet-name').val('null');
	            jQuery('#chalet-address').val('null');
	        }
		}

    </script>

@endsection