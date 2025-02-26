function calculateISS() {
    const aisScores = [
        parseInt(document.getElementById('traumacalc_head').value) || 0,
        parseInt(document.getElementById('traumacalc_face').value) || 0,
        parseInt(document.getElementById('traumacalc_chest').value) || 0,
        parseInt(document.getElementById('traumacalc_abdomen').value) || 0,
        parseInt(document.getElementById('traumacalc_extremity').value) || 0,
        parseInt(document.getElementById('traumacalc_external').value) || 0
    ];

    for (let score of aisScores) {
        if (score < 0 || score > 6) {
            alert("AIS scores must be between 0 and 6.");
            return 0;
        }
    }

    if (aisScores.includes(6)) return 75;

    const sortedScores = aisScores.sort((a, b) => b - a).slice(0, 3);
    return sortedScores[0] ** 2 + sortedScores[1] ** 2 + sortedScores[2] ** 2;
}

function calculateRTS() {
    const gcs = parseInt(document.getElementById('traumacalc_gcs').value) || 0;
    const sbp = parseInt(document.getElementById('traumacalc_sbp').value) || 0;
    const rr = parseInt(document.getElementById('traumacalc_rr').value) || 0;

    if (gcs < 3 || gcs > 15) {
        alert("GCS must be between 3 and 15.");
        return 0;
    }
    if (sbp < 0) {
        alert("Systolic BP must be non-negative.");
        return 0;
    }
    if (rr < 0) {
        alert("Respiratory Rate must be non-negative.");
        return 0;
    }

    const gcsValue = gcs >= 13 ? 4 : gcs >= 9 ? 3 : gcs >= 6 ? 2 : gcs >= 4 ? 1 : 0;
    const sbpValue = sbp > 89 ? 4 : sbp >= 76 ? 3 : sbp >= 50 ? 2 : sbp >= 1 ? 1 : 0;
    const rrValue = (rr >= 10 && rr <= 29) ? 4 : rr > 29 ? 3 : rr >= 6 ? 2 : rr >= 1 ? 1 : 0;

    const rts = (0.9368 * gcsValue) + (0.7326 * sbpValue) + (0.2908 * rrValue);
    return rts.toFixed(3);
}

function calculateTRISS(rts, iss) {
    const age = parseInt(document.getElementById('traumacalc_age').value) || 0;
    const coeffSet = document.getElementById('traumacalc_coeff').value;

    if (age < 0 || age > 120) {
        alert("Age must be between 0 and 120.");
        return { blunt: 0, penetrating: 0 };
    }

    // Age index (A): 0 for ≤54, 1 for >54
    const A = age > 54 ? 1 : 0;

    // Define coefficient sets
    const coefficients = {
        standard: {
            blunt: { b0: -1.2470, b1: 0.9544, b2: -0.0768, b3: -1.9052 },
            penetrating: { b0: -0.6029, b1: 1.1430, b2: -0.1516, b3: -2.6676 }
        },
        qmh: {
            blunt: { b0: -0.4499, b1: 0.8085, b2: -0.0835, b3: -1.7430 },
            penetrating: { b0: -2.5355, b1: 0.9934, b2: -0.0651, b3: -1.1360 }
        }
    };

    // Select coefficients based on user choice
    const selectedCoeffs = coefficients[coeffSet];

    // Calculate b for blunt and penetrating trauma
    const bluntB = selectedCoeffs.blunt.b0 + 
                   (selectedCoeffs.blunt.b1 * rts) + 
                   (selectedCoeffs.blunt.b2 * iss) + 
                   (selectedCoeffs.blunt.b3 * A);
    const penB = selectedCoeffs.penetrating.b0 + 
                 (selectedCoeffs.penetrating.b1 * rts) + 
                 (selectedCoeffs.penetrating.b2 * iss) + 
                 (selectedCoeffs.penetrating.b3 * A);

    // Probability of survival = 1 / (1 + e^(-b))
    const bluntProb = (1 / (1 + Math.exp(-bluntB)) * 100).toFixed(1);
    const penProb = (1 / (1 + Math.exp(-penB)) * 100).toFixed(1);

    return { blunt: bluntProb, penetrating: penProb };
}

function calculateAll() {
    const iss = calculateISS();
    if (iss === 0) return;
    document.getElementById('traumacalc_iss').value = iss;

    const rts = calculateRTS();
    if (rts === 0) return;
    document.getElementById('traumacalc_rts').value = rts;

    const triss = calculateTRISS(rts, iss);
    document.getElementById('traumacalc_trissBlunt').value = triss.blunt + "%";
    document.getElementById('traumacalc_trissPenetrating').value = triss.penetrating + "%";
}

function clearAll() {
    document.querySelectorAll('.traumacalc select').forEach(select => select.value = '0');
    document.querySelectorAll('.traumacalc input[type="text"]').forEach(input => input.value = '');
}