<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="{{asset('assets/general/style.css')}}">
</head>
<style>
  body {
    overflow-y: hidden;
    background: linear-gradient(180deg,
        rgba(61, 170, 51, 0.5),
        rgba(0, 79, 39, 0.5)),
      #33b826;
  }
  thead {
    position: sticky;
    width: 100%;
    left: 0;
    top: 0;
    background: white;
    z-index: 9;
}
</style>

<body>
  <div class="prototype-main">
    <div class="container">
      <div class="row inner-row-main" style="align-items: center;">
        <div class="col-6" style="display: flex;align-items: center;"><img src="{{asset('assets/images/general/logo.svg')}}">&nbsp;
        </div>
        <div class="col-6" style="display: flex;justify-content: flex-end;">
          <h2 id="currentDateTime">2024-04-04 &nbsp; 10:20:00 AM</h2>
        </div>
      </div>
      <div class="main-row-general">
        <div class="row main-row">
          <div class="col-6">BRANCH : {{@$branch->branch_name}}</div>
          <div class="col-6" style="display: flex;justify-content: flex-end;">CONTACT : {{@$branch->branch_contact_number}}</div>
        </div>
        <div class="table-scroll" id="table-container"
          style="height: 250px; overflow-y: scroll;margin: 10px 0;border-radius: 10px;">
          <table class="table consultaions" aria-colspan="5" id="my-table">
            <thead>
              <tr>
                <th colspan="6" class="consutaion-main-head">Consultations</th>
              </tr>
              <tr class="consutaion-sub-head">
                <th>sl no</th>
                <th>Booking ID</th>
                <th>patient</th>
                <th>doctor</th>
                <th>time slot</th>
                <th>token number</th>
              </tr>
            </thead>
            <tbody class="custom-tbody">
                 @php
                    $i = 0;
                 @endphp
				@if($consultations->isEmpty())
				<tr class="consutaion-data">
                    <td>No Consultation Bookings for today</td>
                </tr>
                @else
                    @foreach($consultations as $bookings)
                        <tr class="consutaion-data">
							<td>{{ ++$i }}</td>
							<td>{{@$bookings->booking_reference_number}}</td>
							<td>{{@$bookings->patient['patient_name']}}</td>
							<td>{{@$bookings->doctor['staff_name']}}</td>
							<td>{{ (optional(optional($bookings->staffTimeslot)->timeSlot)->slot_name ?: 'No timeslot selected') . ': ' . 
                           (optional(optional($bookings->staffTimeslot)->timeSlot)->time_from ?: '') . '-' . 
                           (optional(optional($bookings->staffTimeslot)->timeSlot)->time_to ?: '') }}</td>
							<td>{{@$bookings->token_number ?? 0 }}</td>
						</tr>
					@endforeach
               @endif
      
             
            </tbody>
          </table>
        </div>
        <div class="row" style="padding: 0;">
          <div class="col-6" style="display: flex;justify-content: flex-end;padding-right: 0;">
            <div class="table-scroll" id="table-container-wellness" style="height: 250px; overflow-y: scroll;margin: 5px 0;
              border-radius: 10px;
              width: 100%;">
              <table class="table consultaions custom-rounded-table" style="margin: 0;" id="my-table-wellness">
                <thead>
                  <tr>
                    <th colspan="4" class="consutaion-main-head">Wellness</th>
                  </tr>
                  <tr class="consutaion-sub-head">
                    <th>sl no</th>
                    <th>Booking ID</th>
                    <th>wellness</th>
                  </tr>
                </thead>
                <tbody>
                @php
                    $i = 0;
                @endphp
				@if($wellnesses->isEmpty())
				<tr class="consutaion-data">
                    <td>No Wellness Bookings for today</td>
                </tr>
                @else
                    @foreach($wellnesses as $wbookings)
                        <tr class="consutaion-data">
							<td>{{ ++$i }}</td>
							<td>{{@$wbookings->booking_reference_number}}</td>
							<td>
								<ul>
                                   @foreach ($wbookings->wellnessBookings as $wellnessBook)
                                    <li style="list-style: none;">{{ $wellnessBook->wellness['wellness_name'] }}</li>
                                   @endforeach
                                 </ul>
							</td>
						</tr>
					@endforeach
               @endif
      
                 
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-6">
            <div class="table-scroll" id="table-container-consultation" style="height: 250px; overflow-y: scroll;margin: 5px 0;
              border-radius: 10px;
              width: 100%;">
              <table class="table consultaions" style="margin: 0;" id="my-table-consultation">
                <thead>
                  <tr>
                    <th colspan="4" class="consutaion-main-head">therapies</th>
                  </tr>
                  <tr class="consutaion-sub-head">
                    <th>sl no</th>
                    <th>Booking ID</th>
				    <th>Therapy</th>
                  </tr>
                </thead>
                <tbody>
                @php
                    $i = 0;
                @endphp
				@if($therapies->isEmpty())
				<tr class="consutaion-data">
                    <td>No Therapy Bookings for today</td>
                </tr>
                @else
                    @foreach($therapies as $tbookings)
                        <tr class="consutaion-data">
							<td>{{ ++$i }}</td>
							<td>{{@$tbookings->booking_reference_number}}</td>
							<td>
								<ul>
                                @foreach ($tbookings->therapyBookings as $therapyBook)
                                    <li style="list-style: none;">{{ @$therapyBook->therapy['therapy_name'] }}</li>
                                @endforeach
                                </ul>
						    </td>
						</tr>
					@endforeach
               @endif
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
    $(document).ready(function () {
      function updateDateTime() {
        var currentDateTime = new Date();
        var formattedDate = currentDateTime.toLocaleDateString('en-GB', {
          day: 'numeric',
          month: 'numeric',
          year: 'numeric'
        }).split('/').join('-');
        var formattedTime = currentDateTime.toLocaleTimeString();
        $("#currentDateTime").text(formattedDate + ", " + formattedTime);
      }
      updateDateTime();
      setInterval(updateDateTime, 1000);
    });
  </script>
  <script>
    $(document).ready(function () {
      var scrollInterval = setInterval(function () {
        var container = $('#table-container');
        var table = $('#my-table');
        var tableHeight = table.outerHeight();
        var containerHeight = container.height();
        var scrollHeight = tableHeight - containerHeight;
        if (scrollHeight <= 0) return; 

        var scrollTop = container.scrollTop();
        if (scrollTop >= scrollHeight) {
          container.scrollTop(0);
        } else {
          container.scrollTop(scrollTop + 1);
        }
      }, 50);
    });
  </script>
  <script>
    $(document).ready(function () {
      var scrollInterval = setInterval(function () {
        var container = $('#table-container-wellness');
        var table = $('#my-table-wellness');
        var tableHeight = table.outerHeight();
        var containerHeight = container.height();
        var scrollHeight = tableHeight - containerHeight;
        if (scrollHeight <= 0) return;

        var scrollTop = container.scrollTop();
        if (scrollTop >= scrollHeight) {
          container.scrollTop(0);
        } else {
          container.scrollTop(scrollTop + 1);
        }
      }, 50);
    });
  </script>
<script>
  $(document).ready(function () {
    var scrollInterval = setInterval(function () {
      var container = $('#table-container-consultation');
      var table = $('#my-table-consultation');
      var tableHeight = table.outerHeight();
      var containerHeight = container.height();
      var scrollHeight = tableHeight - containerHeight;
      if (scrollHeight <= 0) return; // Don't scroll if content fits container

      var scrollTop = container.scrollTop();
      if (scrollTop >= scrollHeight) {
        container.scrollTop(0);
      } else {
        container.scrollTop(scrollTop + 1);
      }
    }, 50);
  });
</script>
</body>

</html>