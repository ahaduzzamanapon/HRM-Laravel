<div class="row">
    <div class="col-md-6">
        <!-- From Location Field -->
        <div class="form-group">
            {!! Form::label('from_location', 'From Location:') !!}
            {!! Form::text('from_location', null, ['class' => 'form-control', 'id' => 'from_location']) !!}
            <div id="from_map" style="height: 300px; width: 100%;"></div>
            {!! Form::hidden('from_latitude', null, ['id' => 'from_latitude']) !!}
            {!! Form::hidden('from_longitude', null, ['id' => 'from_longitude']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <!-- To Location Field -->
        <div class="form-group">
            {!! Form::label('to_location', 'To Location:') !!}
            {!! Form::text('to_location', null, ['class' => 'form-control', 'id' => 'to_location']) !!}
            <div id="to_map" style="height: 300px; width: 100%;"></div>
            {!! Form::hidden('to_latitude', null, ['id' => 'to_latitude']) !!}
            {!! Form::hidden('to_longitude', null, ['id' => 'to_longitude']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- Distance Field -->
        <div class="form-group">
            {!! Form::label('distance', 'Distance (in km):') !!}
            {!! Form::number('distance', null, ['class' => 'form-control', 'step' => '0.01', 'id' => 'distance', 'readonly' => true]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <!-- Reason Field -->
        <div class="form-group">
            {!! Form::label('reason', 'Reason:') !!}
            {!! Form::text('reason', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- Da Amount Field -->
        <div class="form-group">
            {!! Form::label('da_amount', 'DA Amount:') !!}
            {!! Form::number('da_amount', null, ['class' => 'form-control', 'step' => '0.01']) !!}
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    // Define initMap globally
    function initMap() {
        const fromMap = new google.maps.Map(document.getElementById("from_map"), {
            center: { lat: 23.8103, lng: 90.4125 }, // Default to Dhaka
            zoom: 12,
        });

        const toMap = new google.maps.Map(document.getElementById("to_map"), {
            center: { lat: 23.8103, lng: 90.4125 }, // Default to Dhaka
            zoom: 12,
        });

        const fromInput = document.getElementById("from_location");
        const toInput = document.getElementById("to_location");

        const fromAutocomplete = new google.maps.places.Autocomplete(fromInput);
        const toAutocomplete = new google.maps.places.Autocomplete(toInput);

        fromAutocomplete.bindTo("bounds", fromMap);
        toAutocomplete.bindTo("bounds", toMap);

        const fromMarker = new google.maps.Marker({
            map: fromMap,
            anchorPoint: new google.maps.Point(0, -29),
        });

        const toMarker = new google.maps.Marker({
            map: toMap,
            anchorPoint: new google.maps.Point(0, -29),
        });

        fromAutocomplete.addListener("place_changed", () => {
            fromMarker.setVisible(false);
            const place = fromAutocomplete.getPlace();

            if (!place.geometry || !place.geometry.location) {
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            fromMap.setCenter(place.geometry.location);
            fromMap.setZoom(17);
            fromMarker.setPosition(place.geometry.location);
            fromMarker.setVisible(true);

            document.getElementById("from_latitude").value = place.geometry.location.lat();
            document.getElementById("from_longitude").value = place.geometry.location.lng();

            calculateDistance();
        });

        toAutocomplete.addListener("place_changed", () => {
            toMarker.setVisible(false);
            const place = toAutocomplete.getPlace();

            if (!place.geometry || !place.geometry.location) {
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            toMap.setCenter(place.geometry.location);
            toMap.setZoom(17);
            toMarker.setPosition(place.geometry.location);
            toMarker.setVisible(true);

            document.getElementById("to_latitude").value = place.geometry.location.lat();
            document.getElementById("to_longitude").value = place.geometry.location.lng();

            calculateDistance();
        });
    }

    function calculateDistance() {
        const fromLat = document.getElementById("from_latitude").value;
        const fromLng = document.getElementById("from_longitude").value;
        const toLat = document.getElementById("to_latitude").value;
        const toLng = document.getElementById("to_longitude").value;

        if (fromLat && fromLng && toLat && toLng) {
            const origin = new google.maps.LatLng(fromLat, fromLng);
            const destination = new google.maps.LatLng(toLat, toLng);

            const service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
                {
                    origins: [origin],
                    destinations: [destination],
                    travelMode: "DRIVING",
                },
                (response, status) => {
                    if (status === "OK" && response.rows[0].elements[0].status === "OK") {
                        const distance = response.rows[0].elements[0].distance.value / 1000; // in km
                        document.getElementById("distance").value = distance.toFixed(2);
                    } else {
                        window.alert("Error calculating distance: " + status);
                    }
                }
            );
        }
    }

    // No need for google.maps.event.addDomListener(window, "load", initMap);
    // The callback=initMap in the script URL handles this.
</script>
@endsection