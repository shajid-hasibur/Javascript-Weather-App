<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Weather App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: rgb(182, 226, 254)">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="card mt-5">
                <div class="card-header text-center">
                    <label>Weather App</label>
                </div>
                <div class="card-body">
                    <button style="float: right;" class="btn btn-primary" id="location-button">Get Weather</button>
                    <div class="d-none" id="table-container" style="margin-top: 30px;">
                        <div class="location">
                            <h5>Location</h5>
                            <table class="table table-bordered text-nowrap" id="weather-table-location">
                                <thead>
                                    <tr style="background-color: rgb(182, 226, 254)">
                                        <th>Country</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Local Time</th>
                                        <th>Area</th>
                                        <th>Region</th>
                                        <th>Timezone</th>
                                    </tr>
                                </thead>
                                <tbody id="location-tbody"></tbody>
                            </table>
                        </div>
                        <div class="air-quality">
                            <h5>Air Quality</h5>
                            <table class="table table-bordered text-nowrap" id="weather-table-air-quality">
                                <thead>
                                    <tr style="background-color: rgb(182, 226, 254)">
                                        <th>Carbon Monoxide</th>
                                        <th>Air Quality Index-UK</th>
                                        <th>Nitrogen Dioxide</th>
                                        <th>Ozone</th>
                                        <th>Particulate Matter 2.5</th>
                                        <th>Particulate Matter 10</th>
                                        <th>Sulfur Dioxide</th>
                                        <th>Air Quality Index-US</th>
                                    </tr>
                                </thead>
                                <tbody id="air-quality-tbody"></tbody>
                            </table>
                        </div>
                        <div>
                            <strong>
                            <h5>Weather Statistics</h5>
                            <label>Clouds : </label>
                            <span id="clouds"></span><br>
                            <label>Temperature Feels Like : </label>
                            <span id="feels-like"></span><br>
                            <label>Wind Gust Speed : </label>
                            <span id="wind-gust-speed"></span><span> km</span><br>
                            <label>Humidity : </label>
                            <span id="humidity"></span><br>
                            <label>Day or Night : </label>
                            <span id="isDay"></span><br>
                            <label>Last Updated Time : </label>
                            <span id="lastUpdated"></span><br>
                            <label>Air Pressure in inches of mercury : </label>
                            <span id="airPressure"></span><br>
                            <label>Temperature : </label>
                            <span id="temperature"></span><span> C</span><br>
                            <label>Ultraviolet (UV) index : </label>
                            <span id="uv"></span><br>
                            <label>Visibility : </label>
                            <span id="visibility"></span><span> km</span><br>
                            <label>Wind Directions : </label>
                            <span id="wind-directions"></span><br>
                            <label>Wind Speed : </label>
                            <span id="wind-speed"></span><span> km</span><br>
                        </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let button = document.getElementById("location-button");
        let tableBody = document.getElementById("weather-table-air-quality");
        let table2 = document.getElementById("weather-table-location");

        async function getData(lat, long) {
            const response = await fetch(`http://api.weatherapi.com/v1/current.json?key=f51eabde2b8346eb938120939232010&q=${lat},${long}&aqi=yes`);
            if (response.ok) {
                return await response.json();
            } else {
                throw new Error(`Failed to fetch data. Status: ${response.status}`);
            }
        }

        function success(position) {
            getData(position.coords.latitude, position.coords.longitude)
                .then(result => {
                    console.log(result);
                    weatherTable(result);
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        }

        function failed() {
            console.log("Something went wrong! Failed to get location.");
        }

        button.addEventListener("click", () => {
            button.disabled = true;
            navigator.geolocation.getCurrentPosition(success, failed);
        });

        function weatherTable(result){
            let element = document.getElementById("table-container");
            element.classList.remove("d-none");

            const locationTbody = document.getElementById("location-tbody");
            const airQualityTbody = document.getElementById("air-quality-tbody");
            locationTbody.innerHTML = "";
            airQualityTbody.innerHTML = "";
            //air quality table
            const row = tableBody.insertRow();
            const CarbonMonoxideCell = row.insertCell(0);
            const AirQualityIndexUKCell = row.insertCell(1);
            const NitrogenDioxideCell = row.insertCell(2);
            const OzoneCell = row.insertCell(3);
            const ParticulateMatter2_5Cell = row.insertCell(4);
            const ParticulateMatter10Cell = row.insertCell(5);
            const SulfurDioxideCell = row.insertCell(6);
            const AirQualityIndexUSCell = row.insertCell(7);

            CarbonMonoxideCell.textContent = result.current.air_quality.co;
            AirQualityIndexUKCell.textContent = result.current.air_quality['gb-defra-index'];
            NitrogenDioxideCell.textContent = result.current.air_quality.no2;
            OzoneCell.textContent = result.current.air_quality.o3;
            ParticulateMatter2_5Cell.textContent = result.current.air_quality.pm2_5;
            ParticulateMatter10Cell.textContent = result.current.air_quality.pm10;
            SulfurDioxideCell.textContent = result.current.air_quality.so2;
            AirQualityIndexUSCell.textContent = result.current.air_quality['us-epa-index'];

            // location table
            const row2 = table2.insertRow();
            const CountryCell = row2.insertCell(0);
            const LatitudeCell = row2.insertCell(1);
            const LongitudeCell = row2.insertCell(2);
            const LocalTimeCell = row2.insertCell(3);
            const AreaCell = row2.insertCell(4);
            const RegionCell = row2.insertCell(5);
            const TimezoneCell = row2.insertCell(6);

            CountryCell.textContent = result.location.country;
            LatitudeCell.textContent = result.location.lat;
            LongitudeCell.textContent = result.location.lon;
            LocalTimeCell.textContent = result.location.localtime;
            AreaCell.textContent = result.location.name;
            RegionCell.textContent = result.location.region;
            TimezoneCell.textContent = result.location.tz_id;

            document.getElementById("clouds").textContent = result.current.cloud;
            document.getElementById("feels-like").textContent = result.current.feelslike_c;
            document.getElementById("wind-gust-speed").textContent = result.current.gust_kph;
            document.getElementById("humidity").textContent = result.current.humidity;
            if(result.current.is_day == 0){
                document.getElementById("isDay").textContent = "Night";
            }else{
                document.getElementById("isDay").textContent = "Day"
            }
            document.getElementById("lastUpdated").textContent = result.current.last_updated;
            document.getElementById("airPressure").textContent = result.current.pressure_in;
            document.getElementById("temperature").textContent = result.current.temp_c;
            document.getElementById("uv").textContent = result.current.uv;
            document.getElementById("visibility").textContent = result.current.vis_km;
            document.getElementById("wind-directions").textContent = result.current.wind_dir;
            document.getElementById("wind-speed").textContent = result.current.wind_kph;
        }
    </script>
</body>
</html>
