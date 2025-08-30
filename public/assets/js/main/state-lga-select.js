import { stateLgaData } from './nigeria-states.js';

export function setupStateLgaSelect(stateSelectId, lgaSelectId) {
    const stateSelect = document.getElementById(stateSelectId);
    const lgaSelect = document.getElementById(lgaSelectId);
    // Populate state select
    for (const state in stateLgaData) {
        const opt = document.createElement('option');
        opt.value = state;
        opt.textContent = state;
        stateSelect.appendChild(opt);
    }
    stateSelect.addEventListener('change', function() {
        lgaSelect.innerHTML = '<option value="">Select LGA</option>';
        const lgas = stateLgaData[this.value] || [];
        lgas.forEach(function(lga) {
            const opt = document.createElement('option');
            opt.value = lga;
            opt.textContent = lga;
            lgaSelect.appendChild(opt);
        });
    });
} 