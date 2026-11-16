<?php
function calculateVehicleMetrics($v, $downPct = 20, $tenure = 24, $dailyKm = 20, $fuelPrice = 104) {
    $price   = (float)($v['price'] ?? 0);
    $mileage = max(1, (float)($v['mileage'] ?? 40));
    $maint   = (float)($v['maint'] ?? ($v['maintenance_yearly'] ?? 2000));
    $ins     = (float)($v['ins'] ?? ($v['insurance_yearly'] ?? 4000));

    $loanAmt = $price * (1 - $downPct / 100);
    $r = 0.095 / 12;
    $emi = ($loanAmt > 0 && $tenure > 0)
        ? ($loanAmt * $r * pow(1 + $r, $tenure)) / (pow(1 + $r, $tenure) - 1)
        : 0;

    $monthlyFuel = (($dailyKm * 30) / $mileage) * $fuelPrice;
    $monthlyMaintIns = ($maint + $ins) / 12;
    $monthlyTotal = $emi + $monthlyFuel + $monthlyMaintIns;
    $yearlyTotal  = $monthlyTotal * 12;

    return [
        'emi' => round($emi),
        'monthly_fuel' => round($monthlyFuel),
        'monthly_total' => round($monthlyTotal),
        'yearly_total' => round($yearlyTotal)
    ];
}
?>