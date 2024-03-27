<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Searches</title>
    <!-- Include your CSS file -->
    <link rel="stylesheet" href="css\filter.css">
</head>

<body>
    <header>
        <!-- Include your header -->
    </header>
    <main>
        <div class="container">
            <div class="left-section col-l-3">
                <div class="property-search">
                    <form action="handle_search.php" method="post">
                        <!-- Use classes instead of IDs for consistency -->
                        <label for="location">Property Search</label><br>
                        <input type="text" name="location" id="location"><br>

                        <label for="range">Price Range:</label><br>
                        <input type="range" id="range" name="range" min="0" max="1000000" value="0" step="10000"><br>
                        <output for="range" id="rangeOutput">0 - 1000000</output><br>

                        <label for="propertyType">Property Type</label><br>
                        <select name="propertyType" id="propertyType">
                            <option value="all" selected>All</option>
                            <option value="house">House</option>
                            <option value="apartment">Apartment</option>
                            <option value="condo">Condo</option>
                            <option value="land">Land</option>
                        </select><br>

                        <label for="bedrooms">Bedrooms</label><br>
                        <input type="number" id="bedrooms" name="bedrooms" min="0" max="10" value="0"><br>

                        <label for="bathrooms">Bathrooms</label><br>
                        <input type="number" id="bathrooms" name="bathrooms" min="0" max="10" value="0"><br>

                        <label for="sort">Sort By</label><br>
                        <select name="sort" id="sort">
                            <option value="none" selected>None</option>
                            <option value="asc">ASC</option>
                            <option value="desc">DESC</option>
                        </select><br>

                        <!-- Additional filters -->
                        <label for="amenities">Amenities</label><br>
                        <input type="checkbox" id="pool" name="amenities[]" value="pool">
                        <label for="pool">Pool</label><br>
                        <input type="checkbox" id="gym" name="amenities[]" value="gym">
                        <label for="gym">Gym</label><br>
                        <input type="checkbox" id="parking" name="amenities[]" value="parking">
                        <label for="parking">Parking</label><br>

                        <label for="yearBuilt">Year Built</label><br>
                        <input type="number" id="yearBuilt" name="yearBuilt" min="1900" max="2022"><br>

                        <label for="squareFeet">Square Feet</label><br>
                        <input type="number" id="squareFeet" name="squareFeet" min="0" max="10000"><br>

                        <label for="availability">Availability</label><br>
                        <select name="availability" id="availability">
                            <option value="any" selected>Any</option>
                            <option value="available">Available</option>
                            <option value="sold">Sold</option>
                        </select><br>

                    </form>
                </div>
            </div>
            <div class="right-section col-l-9">
                <!-- Include your PHP code to display filtered results -->
            </div>
        </div>
    </main>

    <script>
        // JavaScript code for range input
        const range = document.getElementById('range');
        const rangeOutput = document.getElementById('rangeOutput');

        range.addEventListener('input', function () {
            const startValue = parseInt(this.min).toLocaleString('en-US');
            const endValue = parseInt(this.value).toLocaleString('en-US');
            const maxPrice = parseInt(this.max).toLocaleString('en-US');

            rangeOutput.textContent = `$${startValue} - $${endValue}`;
            rangeOutput.setAttribute('value', `$${startValue} - $${endValue}`);

            this.style.background = `linear-gradient(to right, #007bff 0%, #007bff ${Math.floor((endValue / maxPrice) * 100)}%, #ccc ${Math.floor((endValue / maxPrice) * 100)}%, #ccc 100%)`;
        });
    </script>
</body>

</html>
