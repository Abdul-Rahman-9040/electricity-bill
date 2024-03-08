<?php

// process_units_month.php

// Check if the form is submitted and the required keys are set in $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["numberOfUnits"], $_POST["selectedMonth"])) {
    // Retrieve values from the form
    $numberOfUnits = $_POST["numberOfUnits"];
    $selectedMonth = $_POST["selectedMonth"];

    // Define average free units and the increase percentage
    $averageFreeUnits = 53;
    $increasePercentage = 0.10;

    // Calculate the average free units with a 10% increase
    $averageFreeUnitsWithIncrease = $averageFreeUnits * (1 + $increasePercentage);

    // Calculate the bill
    if ($numberOfUnits <= $averageFreeUnitsWithIncrease) {
        // Units below or equal to the average free units, no charge
        $electricityBill = 0.0;
    } elseif ($numberOfUnits <= $freeUnitsLimit) {
        // Units below or equal to the 200 units limit, but above average free units
        $unitsAboveAverage = $numberOfUnits - $averageFreeUnitsWithIncrease;
        $electricityBill = $unitsAboveAverage * $ratePerUnitFree;
    } else {
        // Calculate for units above 200
        $unitsAbove200 = $numberOfUnits - $freeUnitsLimit;
        $electricityBill = $unitsAbove200 * $ratePerUnitAbove200;
    }

    // Display the result
    echo "<p>Number of Units: $numberOfUnits</p>";
    echo "<p>Selected Month: $selectedMonth</p>";
    echo "<p>Electricity Bill:₹ $electricityBill </p>";

    // Here, you might want to store the result in a database or perform additional actions

} else {
    // Handle the case where the form is not submitted properly or the required keys are not set
    echo "Error: Form not submitted or missing required parameters.";
}

?>
